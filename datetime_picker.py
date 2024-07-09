import tkinter as tk
from tkcalendar import Calendar

class DateTimePicker(tk.Toplevel):
    def __init__(self, master, entry):
        tk.Toplevel.__init__(self, master)
        self.entry = entry
        self.cal = Calendar(self)
        self.cal.pack(pady=20)
        tk.Button(self, text="OK", command=self.update_entry).pack(pady=10)

    def update_entry(self):
        selected_date = self.cal.selection_get()
        self.entry.delete(0, tk.END)
        self.entry.insert(0, selected_date.strftime("%Y-%m-%d"))
        self.destroy()

class MyApp:
    def __init__(self, root):
        self.root = root
        self.entry = tk.Entry(root)
        self.entry.pack(pady=20)
        tk.Button(root, text="Pick Date", command=self.open_date_picker).pack(pady=10)

    def open_date_picker(self):
        date_picker = DateTimePicker(self.root, self.entry)

if __name__ == "__main__":
    root = tk.Tk()
    app = MyApp(root)
    root.mainloop()
