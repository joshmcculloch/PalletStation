
#include "DHT.h"
#include <ESP8266WiFi.h>
#include <DNSServer.h>
#include <ESP8266WebServer.h>
//#include <WiFiManager.h>

#include <ESP8266HTTPClient.h>
#include <ESP8266httpUpdate.h>


#define DHTPIN 4 
#define SENSEPIN 14 
#define DHTTYPE DHT22 
#define VERSION_STRING "0.5"

#define S1_OPEN   12
#define S1_CLOSE  13
#define S2_OPEN   14
#define S2_CLOSE  5


const char* ssid     = "SolarGarden";
const char* password = "Solaryou17!";

const char* id = "5";
const char* auth = "DDD61575C1CD0A8FC5B856BDF62973B10620F78D6E80460AE423F6D3D42E7EA1";
const char* fmt = "http://sense.joshmcculloch.nz/index.php/api/update/%s/%s?temp=%d&humid=%d&soil=%d&retries=%d&rssi=%d&version=%s";
char url_buffer[200];

DHT dht(DHTPIN, DHTTYPE);

void setup() {
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(SENSEPIN, OUTPUT);

  Serial.begin(9600);

  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  dht.begin();
}

void updateFirmware(const char* path){
  Serial.printf("Updating... %s", path);
  ESPhttpUpdate.update(path);

}

void loop() {
  digitalWrite(LED_BUILTIN, LOW);   
  digitalWrite(SENSEPIN,HIGH);
  delay(2000);  
  float s = (float)analogRead(A0) * 100 / 1024;
  float h = dht.readHumidity();
  float  t = dht.readTemperature();
  if (isnan(h) || isnan(t)) {
    Serial.println(F("Failed to read from DHT sensor!"));
  } else {
    Serial.print(F("DHT "));
    Serial.print(h);
    Serial.print(F("% "));
    Serial.print(t);
    Serial.print(F("°C - "));
  }
  Serial.print(F("Soil "));
  Serial.print(s);
  Serial.println(F("%"));

  
  

  if (WiFi.status() == WL_CONNECTED) {
    int rssi = WiFi.RSSI();
    Serial.print("RSSI");
    Serial.println(rssi);
    int attempt = 0;
    while (attempt < 5) {
      sprintf(url_buffer, fmt, id, auth, (int)t, (int)h, (int)s, (int)attempt, rssi,VERSION_STRING);
      Serial.println(url_buffer);
      
      HTTPClient http;
      http.begin(url_buffer);
      
      int httpCode = http.GET();
      Serial.printf("HTTP: %d\n", httpCode);

      if (httpCode == 610) {
        updateFirmware(http.getString().c_str());
      }
      
      if (httpCode > 0) break;
      attempt++;
    }
    

    
  }
  
  digitalWrite(LED_BUILTIN, HIGH); 
  digitalWrite(SENSEPIN,LOW);
  ESP.deepSleep(60e6*10);                   
}
