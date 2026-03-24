#!/usr/bin/env python3
import OPi.GPIO as GPIO
import time
import os

COIN_PIN = "PA12"
RELAY_PIN = "PA11"

COIN_FILE = "/var/www/html/portal/coins.txt"

GPIO.setmode(GPIO.SUNXI)
GPIO.setwarnings(False)

GPIO.setup(COIN_PIN, GPIO.IN)
GPIO.setup(RELAY_PIN, GPIO.OUT)

GPIO.output(RELAY_PIN, GPIO.HIGH)

print("Coin daemon running...")

def pulse_detected(channel):
    try:
        if not os.path.exists(COIN_FILE):
            count = 0
        else:
            with open(COIN_FILE, "r") as f:
                content = f.read().strip()
                count = int(content) if content else 0

        count += 1

        with open(COIN_FILE, "w") as f:
            f.write(str(count))

        print("Coin inserted:", count)

        time.sleep(0.2)

    except Exception as e:
        print("Error:", e)

GPIO.add_event_detect(COIN_PIN, GPIO.FALLING, callback=pulse_detected, bouncetime=200)

try:
    while True:
        time.sleep(1)
except KeyboardInterrupt:
    GPIO.cleanup()