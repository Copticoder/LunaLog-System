import tkinter as tk
import cv2
from tkinter import messagebox
from tkinter import *
from tkinter import ttk
import requests
import json
import base64
import webbrowser
import speech_recognition as sr
import os


class User:

    def __init__(self, email, password):
        self.email = email
        self.password = password
        self.user_id = None
        self.user_token = None
        self.true_user = self.user_login(email, password)

    def user_login(self, email, password):
        url = 'https://copticon.000webhostapp.com/api/user/login'
        data = {'email': email, 'password': password}
        response = requests.post(url, data)
        decoded_resp = json.loads(response.text)
        print(decoded_resp)
        if not decoded_resp['logged_in']:
            return False

        self.user_id = decoded_resp['user_data']['user_id']
        self.user_token = decoded_resp['user_token'][0]['token_data']
        return True

    def post_log(self, log, tag):
        url = 'https://copticon.000webhostapp.com/api/log/register'
        data = {'user_id': self.user_id, 'log_data': log, 'meta_data': "location", 'tags': tag,
                'token': self.user_token}
        requests.post(url, data)

    def post_img(self, img_name, tag):
        url = 'https://lunalog.000webhostapp.com/api/img/register'
        img = open("Pictures/"+img_name, 'rb')
        encoded_img = base64.b64encode(img.read())
        data = {'img_data': encoded_img, 'user_id': self.user_id, 'token': self.user_token,
                'meta_data': 'location', 'tags': tag}
        requests.post(url, data)

    def post_audio(self, audio_name, tag):
        url = 'https://lunalog.000webhostapp.com/audio/register'
        encoded_audio = base64.b64encode(open("Records/"+audio_name, 'rb').read())
        data = {'user_id': self.user_id, 'token': self.user_token, 'audio_data': encoded_audio,
                'meta_data': 'location', 'tags': tag}
        requests.post(url, data)


class LoginPage:
    def __init__(self):
        tkWindow = Tk()
        tkWindow.geometry('800x500')
        tkWindow.title('Luna Logs')

        emailLabel = Label(tkWindow, text="E-mail")
        emailLabel.grid(row=0, column=0)
        email = StringVar()
        emailEntry = Entry(tkWindow, textvariable=email)
        emailEntry.grid(row=0, column=1)

        passwordLabel = Label(tkWindow, text="Password")
        passwordLabel.grid(row=1, column=0)
        password = StringVar()
        passwordEntry = Entry(tkWindow, textvariable=password, show='*')
        passwordEntry.grid(row=1, column=1)

        login_logs = tk.Text(master=tkWindow, width=40, height=5)
        login_logs.grid(row=7, column=0)

        self.window = tkWindow

        def validate():
            user = User(email.get(), password.get())
            if user.true_user:
                login_logs.insert(tk.END, "Authorized User\n")
                self.window.destroy()
                self.window = ProgramGUI(user)
            else:
                login_logs.insert(tk.END, "Wrong Email or Password\n")

        loginButton = ttk.Button(tkWindow, text="Login", command=validate)
        loginButton.grid(row=4, column=0)

        def callback():
            webbrowser.open('https://lunalog.000webhostapp.com')

        registerButton = ttk.Button(tkWindow, text="Register", command=callback)
        registerButton.grid(row=6, column=0)


class ProgramGUI:
    def __init__(self, user):
        def play_voice():
            voice = listbox_records.get(tk.ANCHOR)
            try:
                os.system("start Records/" + voice)
            except RuntimeError:
                app_logs.insert(tk.END, "No File was selected")

        def view_text_log():
            text_log_name = listbox_text_logs.get(tk.ANCHOR)
            with open("Text Logs/" + text_log_name, "r") as f:
                text_log = f.read()
            tk.messagebox.showinfo(title=text_log_name, message=text_log)

        def show_image():
            image_name = listbox_pictures.get(tk.ANCHOR)
            os.system("start Pictures/" + image_name)

        def post_audio():
            voice_name = listbox_records.get(tk.ANCHOR)
            try:
                user.post_audio(voice_name, emo.get())
            except PermissionError:
                app_logs.insert(tk.END, "No Audio is selected\n")
            os.remove("Records/" + voice_name)
            message = voice_name + " sent Successfully"
            app_logs.insert(tk.END, message + "\n")
            refresh_records_listbox()

        def post_text_log():
            text_log_name = listbox_text_logs.get(tk.ANCHOR)
            try:
                with open("Text Logs/" + text_log_name, "r") as f:
                    text_log = f.read()
            except PermissionError:
                app_logs.insert(tk.END, "No Text Log is selected\n")
            user.post_log(text_log, emo.get())
            os.remove("Text Logs/"+text_log_name)
            message = text_log_name + " sent Successfully"
            app_logs.insert(tk.END, message + "\n")
            refresh_text_logs_listbox()

        def post_image():
            image_name = listbox_pictures.get(tk.ANCHOR)
            try:
                user.post_img(image_name, emo.get())
            except PermissionError:
                app_logs.insert(tk.END, "No Image is selected\n")
            os.remove("Pictures/" + image_name)
            message = image_name + " sent Successfully"
            app_logs.insert(tk.END, message + "\n")
            refresh_pictures_listbox()

        def save_text_log():
            text_log = text_field.get("1.0", tk.END)
            text_names = os.listdir("Text Logs")
            if len(text_names) > 0:
                indices = list()
                for text_name in text_names:
                    indices.append(int(text_name.rpartition(".")[0].rpartition("g")[2]))
                text_index = max(indices) + 1
            else:
                text_index = 0
            with open("Text Logs/text_log" + str(text_index) + ".txt", "w") as f:
                f.write(text_log)
            refresh_text_logs_listbox()

        def refresh_text_logs_listbox():
            listbox_text_logs.delete(0, tk.END)
            list_texts = os.listdir("Text Logs")
            dict_text_logs = dict()
            for log in list_texts:
                dict_text_logs[log] = log
            for x, y in enumerate(dict_text_logs):
                listbox_text_logs.insert(x, y)

        def refresh_records_listbox():
            listbox_records.delete(0, tk.END)
            list_records = os.listdir("Records")
            dict_records = dict()
            for record in list_records:
                dict_records[record] = record
            for x, y in enumerate(dict_records):
                listbox_records.insert(x, y)

        def refresh_pictures_listbox():
            listbox_pictures.delete(0, tk.END)
            list_pictures = os.listdir("Pictures")
            dict_pictures = dict()
            for picture in list_pictures:
                dict_pictures[picture] = picture
            for x, y in enumerate(dict_pictures):
                listbox_pictures.insert(x, y)

        def take_picture():

            video_stream_object = cv2.VideoCapture(0, cv2.CAP_DSHOW)
            cap, frame = video_stream_object.read()
            list_pictures = os.listdir("Pictures")
            if len(list_pictures) > 0:
                indices = list()
                for picture_name in list_pictures:
                    indices.append(int(picture_name.rpartition(".")[0].rpartition("e")[2]))
                picIndex = max(indices) + 1
            else:
                picIndex = 0
            cv2.imwrite("Pictures/picture" + str(picIndex) + ".jpg", frame)
            refresh_pictures_listbox()

        def record_voice():
            app_logs.insert(tk.END, "Recoding Began")

            r = sr.Recognizer()
            with sr.Microphone() as source:
                try:
                    audio = r.listen(source)
                    # this is the line of code where we need to work upon!
                    message = str(r.recognize_google(audio))
                    text_field.insert(tk.END, message)
                except sr.UnknownValueError:
                    app_logs.insert(tk.END, "Unknown Value Error")
                except sr.RequestError:
                    app_logs.insert(tk.END, "Request Error")
                else:
                    pass

            records_name = os.listdir("Records")
            if len(records_name) > 0:
                indices = list()
                for record_name in records_name:
                    indices.append(int(record_name.rpartition(".")[0].rpartition("d")[2]))
                voice_index = max(indices) + 1
            else:
                voice_index = 0

            with open("Records/record" + str(voice_index) + ".wav", "wb") as f:
                f.write(audio.get_wav_data())
            refresh_records_listbox()
            app_logs.insert(tk.END, "Recoding Stopped")

        for file_type in ['Problem', 'Achievement', 'Event']:
            try:
                os.mkdir(file_type)
            except FileExistsError:
                print("The Directory " + file_type + " already exists")

        root = tk.Tk()
        root.title('LunaLog V1.0.0 - '+user.email)
        root.geometry("800x600")
        root.iconbitmap('mic.ico')

        emo = tk.StringVar(root)

        self.photo_voice = PhotoImage(file='microphone.png').subsample(4, 4)
        self.photo_camera = PhotoImage(file='camera.png').subsample(50, 50)

        press_voice_label = ttk.Label(root, text='Press the Mic Button to record Records')
        press_voice_label.grid(row=0, column=0)

        press_voice_button = tk.Button(root, image=self.photo_voice, width=30, height=30, command=record_voice)
        press_voice_button.grid(row=0, column=1)

        press_picture_label = ttk.Label(root, text='Press the Camera Button to take a picture')
        press_picture_label.grid(row=3, column=0)

        press_picture_button = tk.Button(root, image=self.photo_camera, width=30, height=30, command=take_picture)
        press_picture_button.grid(row=3, column=1)

        play_audio_button = tk.Button(root, text="Play", command=play_voice)
        play_audio_button.grid(column=0, row=6)

        post_audio_button = tk.Button(root, text="Post", command=post_audio)
        post_audio_button.grid(column=0, row=7)

        show_image_button = tk.Button(root, text="View", command=show_image)
        show_image_button.grid(column=2, row=6)

        post_image_button = tk.Button(root, text="Post", command=post_image)
        post_image_button.grid(column=2, row=7)

        view_text_log_button = tk.Button(root, text="View", command=view_text_log)
        view_text_log_button.grid(column=1, row=6)

        post_text_log_button = tk.Button(root, text="Post", command=post_text_log)
        post_text_log_button.grid(column=1, row=7)

        press_text_log_button = tk.Button(root, text="Save Text Log", command=save_text_log)
        press_text_log_button.grid(row=1, column=1)

        text_field = tk.Text(master=root, height=4, width=20)
        text_field.grid(row=1, column=0)

        tags_label = ttk.Label(root, text='Tag')
        tags_label.grid(row=2, column=0)

        emo.set('Event')
        popupMenu = tk.OptionMenu(
            root, emo, *{'Problem': 'Problem', 'Event': 'Event', 'Achievement': 'Achievement'})
        popupMenu.grid(row=2, column=1)

        listbox_records = tk.Listbox(root)
        refresh_records_listbox()
        listbox_records.grid(column=0, row=5)

        listbox_text_logs = tk.Listbox(root)
        refresh_text_logs_listbox()
        listbox_text_logs.grid(column=1, row=5)

        listbox_pictures = tk.Listbox(root)
        refresh_pictures_listbox()
        listbox_pictures.grid(column=2, row=5)

        text_files_label = ttk.Label(text="Text Logs")
        text_files_label.grid(column=1, row=4)

        records_label = ttk.Label(text="Records")
        records_label.grid(column=0, row=4)

        pictures_label = ttk.Label(text="Pictures")
        pictures_label.grid(column=2, row=4)

        app_logs = tk.Text(master=root, height=9, width=40)
        app_logs.grid(column=0, row=8)

        self.window = root


program = LoginPage()
program.window.mainloop()
