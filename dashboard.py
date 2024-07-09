import tkinter as tk
import mysql.connector
from tkinter import PhotoImage, messagebox
from PIL import Image, ImageTk  # Import necessary modules from Pillow
from tkinter import font as font
from tkinter import ttk
import datetime
from datetime import datetime
from datetime import timedelta
import subprocess
class DashboardWindow:
    tree = None
    def __init__(self):
        self.window = tk.Tk()
        self.window.title("Dashboard")

        self.window_width = 800
        self.window_height = 500

        self.first_heading = font.Font(family="Arial", size=32)
        self.second_heading = font.Font(family="Arial", size=24)
        self.third_heading = font.Font(family="Arial", size=19)
        self.fourth_font = font.Font(family="Arial", size=16)
        self.normal_heading = font.Font(family="Arial", size=13, weight='bold')

        self.connection = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="gschns-shs"
        )

        # Create a cursor object to interact with the database
        self.cursor = self.connection.cursor()


    def center_window(self):
        screen_width = self.window.winfo_screenwidth()
        screen_height = self.window.winfo_screenheight()

        x_coordinate = (screen_width - self.window_width) // 2
        y_coordinate = (screen_height - self.window_height) // 2

        self.window.geometry(f"{self.window_width}x{self.window_height}+{x_coordinate}+{y_coordinate}")

    def open_file_dialog(self, label_text):
        if label_text == "Face Register":
            # Open get_faces_from_camera_tkinter.py
            subprocess.run(["python", "get_faces_from_camera_tkinter.py"])
        elif label_text == "Load Faces":
            # Open features_extraction_to_csv.py
            subprocess.run(["python", "features_extraction_to_csv.py"])
        elif label_text == "Face Attendance":
            # self.window.destroy()
            subprocess.run(["python", "attendance_taker.py"])

    def side_bar(self):


        label_face_register = tk.Label(self.window, text="Face Register", cursor="hand2", font=self.normal_heading, foreground="blue")
        label_face_load = tk.Label(self.window, text="Load Faces", cursor="hand2", font=self.normal_heading, foreground="blue")
        label_face_attendance = tk.Label(self.window, text="Face Attendance", cursor="hand2", font=self.normal_heading, foreground="blue")

        label_face_register.grid(row=1, column=0, padx=5, pady=(100, 2), sticky=tk.W)
        label_face_load.grid(row=2, column=0, padx=5, pady=2, sticky=tk.W)
        label_face_attendance.grid(row=3, column=0, padx=5, pady=2, sticky=tk.W)

        label_face_register.bind("<Button-1>", lambda event: self.open_file_dialog("Face Register"))
        label_face_load.bind("<Button-1>", lambda event: self.open_file_dialog("Load Faces"))
        label_face_attendance.bind("<Button-1>", lambda event: self.open_file_dialog("Face Attendance"))

        tk.Button(self.window, text="Logout", command=self.logout).grid(row=90, column=0, columnspan=5, padx=40, pady=0, sticky=tk.W)

    def logout(self):
        # Implement your logout logic here
        # For example, destroy the current window and open main.py
        self.window.destroy()
        import main  # Assuming main.py is in the same directory
        main.main()  # Call the main function in main.py


    def determine_attendance_status(self, datetime_str):
        print(datetime_str)
        if datetime_str is None:
            return "Unknown"
        # Convert the formatted_datetime string to a datetime object
        formatted_datetime = datetime.strptime(datetime_str, '%I:%M:%S %p')

        # Calculate the difference between the current time and the attendance time
        time_difference = (datetime.now() - formatted_datetime)

        # If the difference is less than or equal to 15 minutes, consider it as "Present," otherwise "Late"
        if time_difference <= timedelta(minutes=15):
            return "Early"
        else:
            return "Late"
    def right_bar(self):
        right_frame = tk.Frame(self.window, width=650, height=self.window_height, bg="#abaaa7", bd=1, relief="solid")
        right_frame.grid(row=0, column=1, rowspan=99, sticky=tk.NSEW)

        # Creating a Treeview widget for displaying the table
        if not DashboardWindow.tree:
            DashboardWindow.tree = ttk.Treeview(right_frame, columns=("fullname", "date", "datetime", "status"),
                                                show="headings",
                                                selectmode="browse",
                                                height=20)
            DashboardWindow.tree.heading("fullname", text="Name")
            DashboardWindow.tree.heading("date", text="Date")
            DashboardWindow.tree.heading("datetime", text="Time")
            DashboardWindow.tree.heading("status", text="Status")
            DashboardWindow.tree.place(x=20, y=20)

            DashboardWindow.tree.column("fullname", width=240, anchor='w')
            DashboardWindow.tree.column("date", width=150, anchor='center')
            DashboardWindow.tree.column("datetime", width=100, anchor='center')
            DashboardWindow.tree.column("status", width=100, anchor='center')

            # Create a vertical scrollbar
            scrollbar = ttk.Scrollbar(right_frame, orient="vertical", command=DashboardWindow.tree.yview)
            scrollbar.place(x=650, y=20, height=420)
            DashboardWindow.tree.configure(yscrollcommand=scrollbar.set)

            DashboardWindow.tree.tag_configure("data_tag", font=("Arial", 12))

        # Clear the existing items in the Treeview
        for item in DashboardWindow.tree.get_children():
            DashboardWindow.tree.delete(item)

        query = "SELECT fullname, date AS formatted_date, " \
                "DATE_FORMAT(STR_TO_DATE(datetime, '%m/%d/%Y %h:%i:%s %p'), '%h:%i:%s %p') AS formatted_time FROM attendance_history"
        self.cursor.execute(query)
        rows = self.cursor.fetchall()

        print(rows)

        for row in rows:
            status = self.determine_attendance_status(row[2])
            DashboardWindow.tree.insert("", "end", values=(row[0], row[1], row[2], status), tags=("data_tag",))

    def auto_refresh(self):
        self.right_bar()
        self.window.after(1000, self.auto_refresh)

    def run(self):
        self.right_bar()
        self.side_bar()
        self.center_window()
        self.window.mainloop()

def main():
    dashboard_window = DashboardWindow()
    dashboard_window.run()

if __name__ == "__main__":
    main()