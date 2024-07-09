import dlib
import numpy as np
import cv2
import os
import pandas as pd
import time
import logging
from datetime import datetime
import mysql.connector
import tkinter as tk
import pyttsx3
import threading


# Dlib  / Use frontal face detector of Dlib
detector = dlib.get_frontal_face_detector()

# Dlib landmark / Get face landmarks
predictor = dlib.shape_predictor('data/data_dlib/shape_predictor_68_face_landmarks.dat')

# Dlib Resnet Use Dlib resnet50 model to get 128D face descriptor
face_reco_model = dlib.face_recognition_model_v1("data/data_dlib/dlib_face_recognition_resnet_model_v1.dat")
# Connect to the MySQL database

connection = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="gschns-shs"
)

# Create a cursor object to interact with the database
cursor = connection.cursor()


class Face_Recognizer:

    def __init__(self):
        self.font = cv2.FONT_ITALIC

        self.root = tk.Tk()

        self.qcd = cv2.QRCodeDetector()
        self.printed_codes = set()
        self.qr_printed = False  # Initialize qr_printed variable
        self.flag_qr = False  # Initialize flag_qr outside the loop

        self.qr_scan_time = None
        self.qr_timeout_seconds = 5.0

        self.engine = pyttsx3.init()
        self.engine.setProperty('rate', 150)

        #load background image
        self.imgBackground = cv2.imread('Resources/background.png')

        # FPS
        self.frame_time = 0
        self.frame_start_time = 0
        self.fps = 0
        self.fps_show = 0
        self.start_time = time.time()

        # cnt for frame
        self.frame_cnt = 0

        #  Save the features of faces in the database
        self.face_features_known_list = []
        # / Save the name of faces in the database
        self.face_name_known_list = []

        #  List to save centroid positions of ROI in frame N-1 and N
        self.last_frame_face_centroid_list = []
        self.current_frame_face_centroid_list = []

        # List to save names of objects in frame N-1 and N
        self.last_frame_face_name_list = []
        self.current_frame_face_name_list = []

        #  cnt for faces in frame N-1 and N
        self.last_frame_face_cnt = 0
        self.current_frame_face_cnt = 0

        # Save the e-distance for faceX when recognizing
        self.current_frame_face_X_e_distance_list = []

        # Save the positions and names of current faces captured
        self.current_frame_face_position_list = []
        #  Save the features of people in current frame
        self.current_frame_face_feature_list = []

        # e distance between centroid of ROI in last and current frame
        self.last_current_frame_centroid_e_distance = 0

        #  Reclassify after 'reclassify_interval' frames
        self.reclassify_interval_cnt = 0
        self.reclassify_interval = 10

        # Initialize combo box at the class level
        self.combo_box = None

        # Connect to the MySQL database
        self.connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="gschns-shs"
        )

        # Create a cursor object to interact with the database
        self.cursor = self.connection.cursor()

        # Call the method to create and populate combo box
        # self.create_and_populate_combo_box()

        folder_path = 'Resources/Modes'
        self.folder_path = folder_path
        self.mode_list = self.load_modes()

        self.code_printed = False  # Flag to track if the code has been printed
        self.printed_codes = set()
        self.store_recognize_faces = set()
        # self.test_executed = False

    #  "features_all.csv"  / Get known faces from "features_all.csv"
    def get_face_database(self):
        if os.path.exists("data/features_all.csv"):
            path_features_known_csv = "data/features_all.csv"
            csv_rd = pd.read_csv(path_features_known_csv, header=None)
            for i in range(csv_rd.shape[0]):
                features_someone_arr = []
                self.face_name_known_list.append(csv_rd.iloc[i][0])
                for j in range(1, 129):
                    if csv_rd.iloc[i][j] == '':
                        features_someone_arr.append('0')
                    else:
                        features_someone_arr.append(csv_rd.iloc[i][j])
                self.face_features_known_list.append(features_someone_arr)
            logging.info("Faces in Database： %d", len(self.face_features_known_list))
            return 1
        else:
            logging.warning("'features_all.csv' not found!")
            logging.warning("Please run 'get_faces_from_camera.py' "
                            "and 'features_extraction_to_csv.py' before 'face_reco_from_camera.py'")
            return 0

    def update_fps(self):
        now = time.time()
        # Refresh fps per second
        if str(self.start_time).split(".")[0] != str(now).split(".")[0]:
            self.fps_show = self.fps
        self.start_time = now
        self.frame_time = now - self.frame_start_time
        self.fps = 1.0 / self.frame_time
        self.frame_start_time = now

    @staticmethod
    # / Compute the e-distance between two 128D features
    def return_euclidean_distance(feature_1, feature_2):
        feature_1 = np.array(feature_1)
        feature_2 = np.array(feature_2)
        dist = np.sqrt(np.sum(np.square(feature_1 - feature_2)))
        return dist

    # / Use centroid tracker to link face_x in current frame with person_x in last frame
    def centroid_tracker(self):
        for i in range(len(self.current_frame_face_centroid_list)):
            e_distance_current_frame_person_x_list = []
            #  For object 1 in current_frame, compute e-distance with object 1/2/3/4/... in last frame
            for j in range(len(self.last_frame_face_centroid_list)):
                self.last_current_frame_centroid_e_distance = self.return_euclidean_distance(
                    self.current_frame_face_centroid_list[i], self.last_frame_face_centroid_list[j])

                e_distance_current_frame_person_x_list.append(
                    self.last_current_frame_centroid_e_distance)

            last_frame_num = e_distance_current_frame_person_x_list.index(
                min(e_distance_current_frame_person_x_list))
            self.current_frame_face_name_list[i] = self.last_frame_face_name_list[last_frame_num]

    #  cv2 window / putText on cv2 window
    def draw_note(self, img_rd):
        #  / Add some info on windows

        cv2.putText(img_rd, "Frame:  " + str(self.frame_cnt), (55, 180), self.font, 0.5, (0, 255, 0), 1,
                    cv2.LINE_AA)
        cv2.putText(img_rd, "FPS:    " + str(self.fps.__round__(2)), (55, 210), self.font, 0.5, (0, 255, 0), 1,
                    cv2.LINE_AA)
        cv2.putText(img_rd, "Faces:  " + str(self.current_frame_face_cnt), (55, 240), self.font, .5, (0, 255, 0), 1,
                    cv2.LINE_AA)
        cv2.putText(img_rd, "Q: Quit", (55, 630), self.font, 0.5, (0, 0, 255), 1, cv2.LINE_AA)


    # insert data in database
    def attendance(self, name):
        current_date = datetime.now().strftime('%m/%d/%Y')
        time_only = datetime.now().strftime('%H:%M:%S')

        try:
            # Execute the SELECT query with parameters
            select_query = "SELECT time_in_am, time_out_am, time_in_pm, time_out_pm FROM qr_scan WHERE date = %s AND fullname = %s"
            self.cursor.execute(select_query, (current_date, name))

            # Fetch the first row
            existing_entry = self.cursor.fetchone()
            print(existing_entry)

            if existing_entry:
                attendance_query = "SELECT time_in_am, time_out_am, time_in_pm, time_out_pm FROM attendance WHERE date = %s AND fullname = %s"
                self.cursor.execute(attendance_query, (current_date, name))
                attendance_existing = self.cursor.fetchone()
                print(attendance_existing)

                if attendance_existing:
                    att_time_in_am, att_time_out_am, att_time_in_pm, att_time_out_pm = attendance_existing

                    if '7:30:00' <= time_only < '9:00:00':
                        if att_time_out_am:
                            self.engine.say("You already time out")
                        else:
                            update_query = "UPDATE attendance SET time_out_am = %s WHERE fullname = %s"
                            self.cursor.execute(update_query, (time_only, name))
                    elif '11:30:00' <= time_only < '12:30:00':
                        if att_time_out_am:
                            self.engine.say("You already time out")
                        else:
                            update_query = "UPDATE attendance SET time_out_am = %s WHERE fullname = %s"
                            self.cursor.execute(update_query, (time_only, name))
                    elif '12:30:00' <= time_only < '15:30:00':
                        if att_time_in_pm:
                            self.engine.say("You already time in for afternoon")
                        else:
                            update_query = "UPDATE attendance SET time_in_pm = %s WHERE fullname = %s"
                            self.cursor.execute(update_query, (time_only, name))
                    elif '16:30:00' <= time_only < '18:00:00':
                        if att_time_out_pm:
                            self.engine.say("You already time out for afternoon")
                        else:
                            update_query = "UPDATE attendance SET time_out_pm = %s WHERE fullname = %s"
                            self.cursor.execute(update_query, (time_only, name))
                else:
                    # Insert initial attendance record
                    insert_query = "INSERT INTO attendance (date, fullname) VALUES (%s, %s)"
                    self.cursor.execute(insert_query, (current_date, name))
                    print("Done insert")

            else:
                print("No entry found in qr_scan for the given date and name")

            self.connection.commit()

        except Exception as e:
            print(f"An error occurred: {e}")
        finally:
            # Close the cursor and connection
            if 'self.cursor' in locals() and self.cursor is not None:
                self.cursor.close()
            if 'self.connection' in locals() and self.connection is not None:
                self.connection.close()

    def attendance_qr(self, qr_name):
        current_date = datetime.now().strftime('%m/%d/%Y')
        current_date_time = datetime.now().strftime('%m/%d/%Y %H:%M:%S')
        time_only = datetime.now().strftime('%H:%M:%S')

        try:
            select_query = "SELECT time_in_am, time_out_am, time_in_pm, time_out_pm FROM qr_scan WHERE date = %s AND fullname = %s"
            self.cursor.execute(select_query, (current_date, qr_name))

            existing_entry = self.cursor.fetchone()
            print(existing_entry)

            if existing_entry:
                time_in_am, time_out_am, time_in_pm, time_out_pm = existing_entry
                if '11:30:00' <= time_only < '12:30:00':
                    if time_out_am:
                        self.engine.say("You already time out")
                    else:
                        update2 = "UPDATE qr_scan SET time_out_am = %s WHERE fullname = %s"
                        self.cursor.execute(update2, (time_only, qr_name))
                elif '12:30:00' <= time_only < '13:30:00':
                    if time_in_pm:
                        self.engine.say("You already time in for afternoon")
                    else:
                        update3 = "UPDATE qr_scan SET time_in_pm = %s WHERE fullname = %s"
                        self.cursor.execute(update3, (time_only, qr_name))
                elif '16:30:00' <= time_only < '18:00:00':
                    if time_out_pm:
                        self.engine.say("You already time out for afternoon")
                    else:
                        update4 = "UPDATE qr_scan SET time_out_pm = %s WHERE fullname = %s"
                        self.cursor.execute(update4, (time_only, qr_name))
                if '7:30:00' <= time_only < '9:00:00':
                    if time_in_am:
                        self.engine.say("You already time in")
                    else:
                        update4 = "UPDATE qr_scan SET time_in_am = %s WHERE fullname = %s"
                        self.cursor.execute(update4, (time_only, qr_name))
            else:
                insert_query = "INSERT INTO qr_scan (date, fullname, time_in_am) VALUES (%s, %s, %s)"
                self.cursor.execute(insert_query, (current_date, qr_name, time_only))
                print(f"{qr_name} marked as present for {current_date_time}")
                self.connection.commit()

            self.engine.runAndWait()

        except Exception as e:
            print(f"An error occurred: {e}")
        finally:
            # Close the cursor and connection
            if 'cursor' in locals() and cursor is not None:
                cursor.close()
            if 'connection' in locals() and connection is not None:
                connection.close()

    def load_modes(self):
        modePathList = os.listdir(self.folder_path)
        imgModeList = []

        for path in modePathList:
            imgModeList.append(cv2.imread(os.path.join(self.folder_path, path)))

        return imgModeList

    #  Face detection and recognition wit OT from input video stream
    def process(self, stream):
        if self.get_face_database():
            qcd = cv2.QRCodeDetector()
            # code_printed = False  # Flag to track if the code has been printed
            # printed_codes = set()
            ret_qr = False
            while stream.isOpened():
                self.frame_cnt += 1
                logging.debug("Frame " + str(self.frame_cnt) + " starts")
                flag, img_rd = stream.read()
                kk = cv2.waitKey(1)

                faces = detector(img_rd, 0)

                self.last_frame_face_cnt = self.current_frame_face_cnt
                self.current_frame_face_cnt = len(faces)

                self.last_frame_face_name_list = self.current_frame_face_name_list[:]
                self.last_frame_face_centroid_list = self.current_frame_face_centroid_list
                self.current_frame_face_centroid_list = []

                self.imgBackground[162:162 + 480, 55:55 + 640] = img_rd

                global test_executed
                test_executed = False
                if flag:
                    ret_qr, decoded_info, points, _ = qcd.detectAndDecodeMulti(img_rd)
                    if ret_qr:
                        for s, p in zip(decoded_info, points):
                            if s:
                                color = (0, 255, 0)
                                if s not in self.printed_codes and not self.code_printed:
                                    self.printed_codes.add(s)
                                    self.code_printed = True
                                    test_executed = True
                                    print(','.join(self.printed_codes))
                                    self.attendance_qr(s)
                                    self.attendance_qr(s)
                            else:
                                color = (0, 0, 255)

                        self.printed_codes.clear()
                        cv2.polylines(img_rd, [p.astype(int)], True, color, 2)
                        cv2.putText(img_rd, s, (int(p[0, 0]), int(p[0, 1] - 10)), cv2.FONT_HERSHEY_SIMPLEX, 0.5, color,
                                    1)
                    self.imgBackground[162:162 + 480, 55:55 + 640] = img_rd

                    if not ret_qr:
                        self.code_printed = False

                        self.imgBackground[162:162 + 480, 55:55 + 640] = img_rd


                if (self.current_frame_face_cnt == self.last_frame_face_cnt) and (
                        self.reclassify_interval_cnt != self.reclassify_interval):
                    logging.debug("scene 1:   No face cnt changes in this frame!!!")

                    self.current_frame_face_position_list = []

                    if "unknown" in self.current_frame_face_name_list:
                        self.reclassify_interval_cnt += 1

                    if self.current_frame_face_cnt != 0:
                        for k, d in enumerate(faces):
                            self.current_frame_face_position_list.append(tuple(
                                [faces[k].left(), int(faces[k].bottom() + (faces[k].bottom() - faces[k].top()) / 4)]))
                            self.current_frame_face_centroid_list.append(
                                [int(faces[k].left() + faces[k].right()) / 2,
                                 int(faces[k].top() + faces[k].bottom()) / 2])

                            img_rd = cv2.rectangle(img_rd,
                                                   tuple([d.left(), d.top()]),
                                                   tuple([d.right(), d.bottom()]),
                                                   (255, 255, 255), 2)

                    if self.current_frame_face_cnt != 1:
                        self.centroid_tracker()

                    for i in range(self.current_frame_face_cnt):
                        img_rd = cv2.putText(img_rd, self.current_frame_face_name_list[i],
                                             self.current_frame_face_position_list[i], self.font, 0.8, (0, 255, 255), 1,
                                             cv2.LINE_AA)


                    # Overlay the face recognition result onto the background image

                    self.imgBackground[162:162 + 480, 55:55 + 640] = img_rd

                else:
                    logging.debug("scene 2: / Faces cnt changes in this frame")
                    self.current_frame_face_position_list = []
                    self.current_frame_face_X_e_distance_list = []
                    self.current_frame_face_feature_list = []
                    self.reclassify_interval_cnt = 0

                    if self.current_frame_face_cnt == 0:
                        logging.debug("  / No faces in this frame!!!")
                        self.current_frame_face_name_list = []
                    else:
                        logging.debug("  scene 2.2  Get faces in this frame and do face recognition")
                        self.current_frame_face_name_list = []
                        for i in range(len(faces)):
                            shape = predictor(img_rd, faces[i])
                            self.current_frame_face_feature_list.append(
                                face_reco_model.compute_face_descriptor(img_rd, shape))
                            self.current_frame_face_name_list.append("unknown")

                        self.imgBackground[162:162 + 480, 55:55 + 640] = img_rd
                        # self.draw_note(self.imgBackground)

                        for k in range(len(faces)):
                            logging.debug("  For face %d in current frame:", k + 1)
                            self.current_frame_face_centroid_list.append(
                                [int(faces[k].left() + faces[k].right()) / 2,
                                 int(faces[k].top() + faces[k].bottom()) / 2])

                            self.current_frame_face_X_e_distance_list = []

                            self.current_frame_face_position_list.append(tuple(
                                [faces[k].left(), int(faces[k].bottom() + (faces[k].bottom() - faces[k].top()) / 4)]))

                            for i in range(len(self.face_features_known_list)):
                                if str(self.face_features_known_list[i][0]) != '0.0':
                                    e_distance_tmp = self.return_euclidean_distance(
                                        self.current_frame_face_feature_list[k],
                                        self.face_features_known_list[i])
                                    logging.debug("      with person %d, the e-distance: %f", i + 1, e_distance_tmp)
                                    self.current_frame_face_X_e_distance_list.append(e_distance_tmp)
                                else:
                                    self.current_frame_face_X_e_distance_list.append(999999999)

                            similar_person_num = self.current_frame_face_X_e_distance_list.index(
                                min(self.current_frame_face_X_e_distance_list))

                            if min(self.current_frame_face_X_e_distance_list) < 0.4:
                                self.current_frame_face_name_list[k] = self.face_name_known_list[similar_person_num]
                                logging.debug("  Face recognition result: %s",
                                              self.face_name_known_list[similar_person_num])

                                # Insert attendance record
                                nam = self.face_name_known_list[similar_person_num]
                                print(type(self.face_name_known_list[similar_person_num]))
                                print(nam)
                                self.attendance(nam)
                                # cv2.imshow("Face Attendance", img_rd)
                            else:
                                logging.debug("  Face recognition result: Unknown person")

                # print(nam)
                if kk == ord('q'):
                    break

                self.update_fps()
                cv2.imshow("Face Attendance", self.imgBackground)

                logging.debug("Frame ends\n\n")


    def run(self):

        cap = cv2.VideoCapture(0)
        self.process(cap)
        cap.release()
        cv2.destroyAllWindows()

def main():

    logging.basicConfig(level=logging.INFO)
    Face_Recognizer_con = Face_Recognizer()
    Face_Recognizer_con.run()

if __name__ == '__main__':
    main()
