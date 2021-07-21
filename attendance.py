#Modify URLs at line 111 and line 123

#importing all the required modules
from kivymd.app import MDApp
from kivy.core.window import Window
from kivy.uix.widget import Widget
from kivy.uix.boxlayout import BoxLayout
from kivymd.toast import toast

import requests
import webbrowser
import speech_recognition as sr


Window.size = (600, 832)    # Defining Window size

# initiating the variables
clas = ''
count = 0
string = ""
present_record = []
absent_record = []
record = []
attendance = ""
presents = ""

r = sr.Recognizer()    # Creating the Recognizer class
mic = sr.Microphone()   # Creating the Microphone class


class Attendance(BoxLayout):

    def _init_(self, **kwargs):
        super(Attendance, self)._init_(**kwargs)

    def audioInput(self):   # Taking the audio input
        global audio
        with mic as source:
            print("\n")
            toast('Wait for a sec...')
            r.adjust_for_ambient_noise(source, duration=1)
            audio = r.listen(source)


    def recognize(self, audio):    # Recognizing the audio
        global attendance, string
        try:
            temp=r.recognize_google(audio)
            # print(temp)
            attendance += "".join(temp)
            print(attendance,type(attendance))
            for i in range(len(attendance)):
                attendance = attendance.replace(" ", "")
            attendance = attendance.split("absent")

            for i in range(len(attendance)):
                string += attendance[i]
        except sr.RequestError as e:  
            print("error; {0}".format(e))
        except sr.UnknownValueError as ue:
            print("error; {0}".format(ue))


    def captureRecords(self):   # Capturing the records
        global count, string
        v = 1
        for i in range(len(attendance)):
            for _ in range(len(attendance[i])):
                count += 1
                if count > 9 and count < 99:
                    v = 2
                elif count > 99 and count < 999:
                    v = 3
            print(attendance[-1][-1],attendance[i][-v])
            if attendance[-1][-1] not in attendance[i][-v]:
                absent_record.append(attendance[i][-v:])

        v = 1; i = 0
        while i in range(len(string)):
            if i > 8 and i < 99:
                v = 2
            elif i > 99 and i < 999:
                v = 3
            if string[i : i+v] not in absent_record:
                present_record.append(string[i : i+v])
            i += v

        record.append("Present: ")
        for i in range(len(present_record)):
            record.append(present_record[i])
            record.append(", ")
        record.append(" || Absent: ")
        for i in range(len(absent_record)):
            record.append(absent_record[i])
            record.append(", ")
        record.append(" |end| ")

        global presents
        presents += '-'
        for i in range(len(present_record)):
            presents += present_record[i].rjust(2, '0') + '-'

        toast("Attendance Logged")


    def createFile(self):   # Logging everything in a file in loacl device
        with open("attendance_log.txt", "a+") as att:   # File will be opened in the same directory as this code
            for i in range(len(record)):
                att.write(record[i])
            att.close()
        print("\n")
        print("Attendance Logged at /xampp/htdocs/Smart-Attendace/attendance_log.txt")
        print()


    def getClass(self):
        global clas
        clas = self.ids.cls.text
        return clas


    def passingToDatabase(self): # Passing ll the records to the database
        global clas
        clas = clas.upper()
        ip = 'http://localhost/Smart-Attendace/index.php' # Modify this url  < This url directs to the LOGIN page >
        data = presents #example of class
        token = "182ED54D634B78F47A31A68360C152BA"  #token is fixed
        url= ip+"Smart-Attendace/store.php?class="+clas+"&data="+data+"&token="+token

        requests.get(url)
        print("Data dumped Successfully!")


    def viewDatabase(self):    # Requesting the database to open on browser
        url = "http://localhost/Smart-Attendace/attendance.php"  # Modify this url  < URL directs to 'attendance' page >

        webbrowser.open(url)


    def startApp(self):    # Calling all the required functions at app start
        self.audioInput()
        self.recognize(audio)
        self.captureRecords()
        self.getClass()
        self.createFile()
        self.passingToDatabase()
    def __draw_shadow__(self, origin, end, context=None):
        pass



class attendanceApp(MDApp):
    def buid(self):
        return Attendance()
    def __draw_shadow__(self, origin, end, context=None):
        pass


if __name__ == "__main__":
    attendanceApp().run()
