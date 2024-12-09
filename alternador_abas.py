import time
import win32com.client
from pynput import keyboard
import threading

shell = win32com.client.Dispatch("WScript.Shell")
running = False
def alternar_abas(intervalo=13):
    global running
    while running:
        shell.SendKeys("^{PGDN}")
        time.sleep(intervalo)
def on_press(key):
    global running
    try:
        if key == keyboard.Key.f4: 
            if not running:
                running = True
                print("Processo iniciado!")
                threading.Thread(target=alternar_abas, daemon=True).start()
        elif key == keyboard.Key.f6: 
            running = False
            print("Processo parado!")
    except Exception as e:
        print(f"Erro: {e}")
listener = keyboard.Listener(on_press=on_press)
listener.start()

print("Pressione F4 para iniciar e F6 para parar.")
while True:
    time.sleep(1) 
