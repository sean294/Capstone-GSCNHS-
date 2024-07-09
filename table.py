import tkinter as tk
from tkinter import ttk
import mysql.connector
from PIL import Image, ImageTk
from io import BytesIO


def display_data():
    # Connect to MySQL database
    connection = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="gschns-shs"
    )

    # Create cursor
    cursor = connection.cursor()

    # Execute query
    cursor.execute("SELECT image, fullname, gender FROM employees")

    # Fetch all rows
    rows = cursor.fetchall()

    # Clear existing data in treeview
    for i in tree.get_children():
        tree.delete(i)

    # Insert data into treeview
    for row in rows:
        # Convert image data to a format compatible with displaying images in Tkinter
        image_data = row[0]
        if image_data:
            image = Image.open(BytesIO(image_data))
            image = image.resize((50, 50), Image.ANTIALIAS)  # Resize image as needed
            image = ImageTk.PhotoImage(image)
        else:
            # If no image is available, use a placeholder
            image = None

        # Insert row data into treeview
        tree.insert('', 'end', values=(image, row[1], row[2]))

    # Close cursor and connection
    cursor.close()
    connection.close()


# Create tkinter window
root = tk.Tk()
root.title("MySQL Data Display")

# Create Treeview widget
tree = ttk.Treeview(root, columns=("Image", "Full Name", "Gender"), show="headings")
tree.heading("Image", text="Image")
tree.heading("Full Name", text="Full Name")
tree.heading("Gender", text="Gender")
tree.pack(pady=10)

# Button to display data
display_button = tk.Button(root, text="Display Data", command=display_data)
display_button.pack(pady=5)

# Run the tkinter event loop
root.mainloop()
