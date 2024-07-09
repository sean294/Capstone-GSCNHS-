import tkinter as tk
from tkinter import PhotoImage, messagebox
from PIL import Image, ImageTk  # Import necessary modules from Pillow
import mysql.connector
import hashlib
import bcrypt

class LoginWindow:
    def __init__(self):
        self.window = tk.Tk()
        self.window.title("Login")
        self.window.geometry("400x300")

        self.window_width = 400
        self.window_height = 300

        self.window.configure(bg="#e0e0e0")

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

    def add_input_fields(self):
        # Username Label and Entry
        tk.Label(self.window, text="Username : ").grid(row=1, column=0, padx=100, pady=5, sticky=tk.W)
        self.username_entry = tk.Entry(self.window)
        self.username_entry.grid(row=1, column=0, padx=170, pady=5, sticky=tk.W)

        # Password Label and Entry (show="*" for password)
        tk.Label(self.window, text="Password : ").grid(row=2, column=0, padx=100, pady=5, sticky=tk.W)
        self.password_entry = tk.Entry(self.window, show="*")
        self.password_entry.grid(row=2, column=0, padx=170, pady=5, sticky=tk.W)

        tk.Button(self.window, command=self.login, text="Login").grid(row=3, column=0, padx=175, pady=(10, 0), sticky=tk.W)

    def add_image(self):
        # Load the image
        img = Image.open("Resources/facial-icon.png")  # Replace with the path to your image file
        img = img.resize((50, 50))  # Adjust the size as needed
        photo = ImageTk.PhotoImage(img)

        # Create a label to display the image
        image_label = tk.Label(self.window, image=photo)
        image_label.photo = photo  # To prevent the image from being garbage collected
        image_label.grid(row=0, column=0, padx=170, pady=(40,20), sticky=tk.W)

    def redirect(self):

        import dashboard  # Assuming main.py is in the same directory
        dashboard.main()  # Call the main function in main.py

    def login(self):
        username = self.username_entry.get()
        password = self.password_entry.get()

        query = "SELECT u.username, u.password , emp.fullname FROM users u LEFT JOIN employees emp on emp.employee_id = u.employee_id WHERE username = %s"
        self.cursor.execute(query, (username,))
        result = self.cursor.fetchone()
        print(result)

        if result:
            hashed_password_from_db = result[1].encode('utf-8')  # Assuming the hashed password is stored in result[1]

            if bcrypt.checkpw(password.encode('utf-8'), hashed_password_from_db):
                messagebox.showinfo("Login Success", f"Welcome, {result[2]}")
                self.window.destroy()
                self.redirect()
            else:
                messagebox.showerror("Login Failed", "Invalid username or password")
        else:
            messagebox.showerror("Login Failed", "Invalid username or password")

    def run(self):
        self.center_window()
        self.add_image()
        self.add_input_fields()
        self.window.mainloop()

def main():
    login_window = LoginWindow()
    login_window.run()

if __name__ == "__main__":
    main()
