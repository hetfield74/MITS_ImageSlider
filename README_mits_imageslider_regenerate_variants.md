# README – MITS ImageSlider: Bilder-Varianten regenerieren

Datei: `admin/mits_imageslider_regenerate_variants.php`

Dieses Tool erzeugt für bereits vorhandene ImageSlider-Bilder zusätzliche Bildvarianten (WebP + Fallback + mehrere Auflösungen für `srcset`) und kann bei Bedarf die gespeicherten Bildpfade sowie Breite/Höhe in der Datenbank aktualisieren.

---

## 1) Zweck

Mit `mits_imageslider_regenerate_variants.php` kannst du bestehende Sliderbilder nachträglich optimieren, ohne sie erneut hochzuladen:

- **WebP erzeugen** (wenn der Server/Webspace WebP über GD unterstützt)
- **Fallback-Dateien** (JPG/PNG) in konsistenter Form sicherstellen
- **mehrere Auflösungen** als Derivate in `srcset/` erzeugen
- **optional**: **DB-Pfadfelder** und **Width/Height** (für weniger CLS) aktualisieren

---

## 2) Voraussetzungen

1) Modul aktiv (im Shop-Backend/Config):
- `MODULE_MITS_IMAGESLIDER_STATUS = true`

2) Helper-Datei vorhanden:
- `includes/external/mits_imageslider/functions/images.php`

3) Serverfunktionen:
- **Lesen/Resizen**: GD muss JPG/PNG/GIF lesen können
- **WebP**: `imagewebp()` muss verfügbar sein (GD mit WebP)

> Tipp: Wenn keine WebP-Dateien entstehen, ist häufig `imagewebp()` nicht verfügbar.

---

## 3) Was wird verarbeitet?

Pro Datensatz (Slider + Sprache) prüft das Tool diese Felder:

- Desktop: `imagesliders_image`
- Tablet: `imagesliders_tablet_image`
- Mobile: `imagesliders_mobile_image`

Verarbeitet werden nur Dateien, die:
- im Dateisystem existieren (unterhalb von `DIR_FS_CATALOG_IMAGES`)
- eine unterstützte Endung haben: `jpg`, `jpeg`, `jpe`, `png`, `gif`, `webp`

Nicht unterstützte Formate (z.B. SVG/AVIF) werden übersprungen.

---

## 4) Welche Varianten werden erzeugt?

### 4.1 Profile (Standardwerte)

**mobile**
- Basis max. Breite: **750px**
- `srcset`-Breiten: **320, 480, 640, 750**

**tablet**
- Basis max. Breite: **1280px**
- `srcset`-Breiten: **768, 1024, 1280**

**desktop**
- Basis max. Breite: **2560px**
- `srcset`-Breiten: **1280, 1600, 1920, 2560**

### 4.2 Ablage / Dateinamen

Beispiel: Basisdatei liegt hier:
- `imagesliders/de/mobile/banner.jpg`

Dann erzeugt das Tool:
- WebP-Basis daneben: `imagesliders/de/mobile/banner.webp`
- Derivate im Unterordner:
  - `imagesliders/de/mobile/srcset/banner-320.jpg`
  - `imagesliders/de/mobile/srcset/banner-320.webp`
  - `imagesliders/de/mobile/srcset/banner-480.jpg`
  - `imagesliders/de/mobile/srcset/banner-480.webp`
  - …

### 4.3 Fallback-Format (JPG/PNG)

- Wenn Transparenz nötig (PNG/GIF), bleibt Fallback **PNG**
- Sonst wird Fallback **JPG**
- Endungen `jpeg`/`jpe` werden auf `jpg` normalisiert

### 4.4 Upscaling?



### 4.5 Wichtiger Hinweis zu GIF (Animationen)
GIF-Dateien werden **nicht konvertiert** und es werden **keine Varianten (kein WebP, kein srcset, kein Resize)** erzeugt. Hintergrund: animierte GIFs würden bei einer GD-basierten Verarbeitung meist auf ein einzelnes Frame reduziert und damit ihre Animation verlieren. Das Tool belässt GIFs daher unverändert, damit Slider-Animationen erhalten bleiben.


Nein. Bilder werden **nicht vergrößert**:
- `srcset`-Varianten werden nur erzeugt, wenn die Zielbreite **<= Original/Basis** ist.

---

## 5) Bedienung

### 5.1 Datei hochladen
Die Datei muss im Admin-Verzeichnis liegen:
- `/admin/mits_imageslider_regenerate_variants.php`

### 5.2 Aufruf im Browser
Im Admin eingeloggt öffnen:
- `https://DEIN-SHOP/admin/mits_imageslider_regenerate_variants.php`

### 5.3 Empfohlener Ablauf
1) **Scan (Prüfen)** ausführen (keine Änderungen)
2) **Backup erstellen** (Dateien + DB)
3) **Execute (Erzeugen)** ausführen
4) Wiederholen (Batchweise), bis alle Datensätze durch sind

---

## 6) Modi

### `mode=scan` (nur prüfen)
- Erzeugt **keine** Dateien
- Ändert **keine** DB
- Zeigt pro Bild: ob WebP / `srcset/` bereits vorhanden ist bzw. was fehlen würde

### `mode=execute` (Varianten erzeugen)
- Erzeugt/überschreibt Varianten (WebP + `srcset/`)
- **Optional**: DB-Pfadfelder und Breite/Höhe aktualisieren

> DB-Updates werden nur ausgeführt, wenn du sie im Tool explizit bestätigst (Sicherheitsmechanismus).

---

## 7) Parameter (URL)



## Hinweis: Execute ohne DB-Bestätigung (confirm=YES)
Im Modus `execute` gilt:
- **ohne** Bestätigung (`confirm` nicht `YES`): es werden nur „sichere“ Basisformate **JPG/PNG** verarbeitet, damit sich keine Dateinamen/Dateitypen ändern, ohne dass die Datenbank nachgezogen wird.
- **mit** Bestätigung (`confirm=YES`): es können auch Fälle verarbeitet werden, bei denen sich der Dateiname/Dateityp ändert (z.B. `jpeg/jpe → jpg`, `webp → jpg`), und die Datenbank wird entsprechend aktualisiert.

Empfehlung: Für einen vollständigen Lauf immer zuerst `scan` nutzen, dann ein Backup erstellen und anschließend `execute` **mit** Bestätigung durchführen.


Du kannst das Tool in Batches ausführen:

- `mode`  
  `scan` oder `execute`

- `limit`  
  Anzahl Datensätze pro Batch (Standard: 25)

- `offset`  
  Startposition im Datensatz-Durchlauf

### Beispiele

Scan, 25er Batch ab Anfang:
```
/admin/mits_imageslider_regenerate_variants.php?mode=scan&limit=25&offset=0
```

Execute, 50er Batch ab Datensatz 100:
```
/admin/mits_imageslider_regenerate_variants.php?mode=execute&limit=50&offset=100
```

Im Tool gibt es außerdem Buttons wie „Nächsten Batch“.

---

## 8) Datenbank-Änderungen (nur im bestätigten Execute)

Wenn DB-Update aktiv ist, kann das Tool:

1) **Pfade normalisieren**, z.B.
- `…/bild.jpeg` → `…/bild.jpg`

2) **Format anpassen**, z.B.
- `…/bild.gif` → `…/bild.png` (falls nötig/so implementiert)

3) **Width/Height aktualisieren** anhand der tatsächlichen Basisdatei:
- `imagesliders_image_width` / `imagesliders_image_height`
- `imagesliders_tablet_image_width` / `imagesliders_tablet_image_height`
- `imagesliders_mobile_image_width` / `imagesliders_mobile_image_height`

---

## 9) Ausgabe / Fehlermeldungen

Typische Log-Zeilen:

- `missing ...`  
  Datei existiert nicht (Pfad stimmt nicht / Datei fehlt)

- `skip (ext) ...`  
  Dateiendung nicht unterstützt

- `generated variants ...`  
  Varianten wurden erzeugt (Execute)

- `path updated ...`  
  DB-Pfad wurde angepasst

- `dims ... -> ...`  
  Breite/Höhe wurden aktualisiert

---

## 10) Best Practices / Hinweise

- **Vor Execute immer Backup**:
  - Ordner: `/images/imagesliders/…`
  - DB: mindestens Tabelle `mits_imageslider_info`

- **Cache leeren** (wenn du Template-/HTML-Caching nutzt), damit neues `<picture>`/`srcset` sofort sichtbar wird.

- Bei sehr vielen Bildern:
  - starte mit `limit=25`, später erhöhen (z.B. 50 oder 100), je nach Serverleistung.

- Wenn WebP nicht entsteht:
  - prüfe `imagewebp()` (GD-WebP Support)

---

## 11) Support / Troubleshooting

Wenn im Frontend nur Desktop geladen wird und kein `<picture>` erscheint:
- Prüfe im HTML-Quelltext nach `<picture>` bzw. `type="image/webp"`.
- Prüfe, ob **mobile/tablet Dateien** im Dateisystem existieren und DB-Felder gefüllt sind.
- Prüfe, ob die Frontend-Funktion die *relativen Pfade* korrekt übergibt (z.B. `mobile_rel`, `tablet_rel`, `main_rel`).

---

**Stand:** 2026-03-04
