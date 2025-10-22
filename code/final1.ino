#include <SPI.h>
#include <MFRC522.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h> 
#include <ESP8266WebServer.h>
#include <ESP8266HTTPClient.h>
#include <LiquidCrystal_I2C.h>
#include "StringSplitter.h"

const char *ssid = "Monitor"; 
const char *password = "monitor2021";


constexpr uint8_t RST_PIN = D4;     
constexpr uint8_t SS_PIN   =D8; 

int lcdColumns = 16;
int lcdRows = 2;

LiquidCrystal_I2C lcd(0x27, lcdColumns, lcdRows);  

MFRC522 rfid(SS_PIN, RST_PIN);
MFRC522::MIFARE_Key key; 

byte nuidPICC[4];
WiFiClient wifiClient;

const int pinBuzz=D0;
const int pinCamera=D3;

void setup() { 
  
  delay(1000);
  Serial.begin(115200);
  
  lcd.init();                    
  lcd.backlight();

  lcd.setCursor(0,0);
  lcd.print("  ABSEN MAKMUR  ");

  lcd.setCursor(0, 1);
  lcd.print("     SEJATI     "); 
  
  pinMode(pinCamera,OUTPUT);
  digitalWrite(pinCamera,HIGH);
       
  pinMode(pinBuzz,OUTPUT);
  digitalWrite(pinBuzz,HIGH);

  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot
  
  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP  
  SPI.begin(); 
  rfid.PCD_Init();
  digitalWrite(pinBuzz,LOW);
  delay(2000);
  digitalWrite(pinBuzz,HIGH);
  Serial.println("Redy");
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("      SCAN      ");

  lcd.setCursor(0, 1);
  lcd.print("   YOUR CARD    ");  
}
 
void loop() {

 
    
  // Look for new cards
  if ( ! rfid.PICC_IsNewCardPresent())
    return;

  // Verify if the NUID has been readed
  if ( ! rfid.PICC_ReadCardSerial())
    return;

    String content= "";

    for (byte i = 0; i < 4; i++) {
    content +=
      (rfid.uid.uidByte[i] < 0x10 ? "0" : "") +
      String(rfid.uid.uidByte[i], HEX) +
      (i != rfid.uid.size - 1 ? "" : "");      
      
    }
  content.trim();   
  content.toUpperCase();

  digitalWrite(pinBuzz,LOW);
  delay(500);
  digitalWrite(pinBuzz,HIGH);

  HTTPClient http;

  String getData, Link;

  getData = "?Data='" + content + "'";
  Link = "http://lab-android.com/MakmurSejati/esp.php" + getData;
  http.begin(wifiClient,Link);
  
  int httpCode = http.GET(); 
  String payload = http.getString();  

  lcd.clear();
  if(payload.length()){
    lcd.setCursor(0,0);
    lcd.print(" Absen Berhasil ");
   
    lcd.setCursor(4,1);
    lcd.print(payload);    
  }else{
    lcd.setCursor(0,0);
    lcd.print("  Kartu  Tidak  ");
   
    lcd.setCursor(0,1);
    lcd.print("    Terdaftar   ");    
  }

    
  digitalWrite(pinCamera,LOW);
  delay(500);
  digitalWrite(pinCamera,HIGH);

  delay(2000);
  http.end(); 
  
  rfid.PICC_HaltA();

  rfid.PCD_StopCrypto1();
  Serial.println("Redy");
  lcd.setCursor(0,0);
  lcd.print("      SCAN      ");

  lcd.setCursor(0, 1);
  lcd.print("   YOUR CARD    ");  
}

