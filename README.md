
# MITS ImageSlider für modified eCommerce Shopsoftware
(c) Copyright by Hetfield - MerZ IT-SerVice

- Author: 	Hetfield - https://www.merz-it-service.de
- Version: 	modified eCommerce Shopsoftware ab der Version 2.0.0.0 rev 9678

Original nur von hier:
https://www.merz-it-service.de/Shop-Module/MITS-ImageSlider-fuer-modified-eCommerce-Shopsoftware-by-Hetfield.html

Download bei GitHub:
https://github.com/hetfield74/MITS_ImageSlider/

<hr />

### Lizenzinformationen:

Diese Erweiterung ist unter der GNU/GPL lizensiert. Eine Kopie der Lizenz liegt diesem Modul bei 
oder kann unter der URL http://www.gnu.org/licenses/gpl-2.0.txt heruntergeladen werden. Die 
Copyrighthinweise müssen erhalten bleiben, bzw. mit eingebaut werden. Zuwiderhandlungen verstoßen 
gegen das Urheberrecht und die GPL und werden zivil- und strafrechtlich verfolgt!

<hr />

## Anleitung für das Modul MITS ImageSlider

Sie finden die aktuellste Installationsanleitung unter https://imageslider.merz-it-service.de/readme.html

<hr />

## 1. Installation

Systemvoraussetzung: Funktionsfähige modified eCommerce Shopsoftware ab der Version 2.0.0.0 rev 9678

Vor der Installation des Moduls sichern sie bitte komplett ihre aktuelle Shopinstallation (Dateien und Datenbank)!
Für eventuelle Schäden übernehmen wir keine Haftung!
Die Installation und Nutzung des Moduls MITS ImageSlider erfolgt auf eigene Gefahr!

Die Installation des Modul MITS ImageSlider ist in der aktuellen Shopversion 2.x ziemlich einfach.

    1. Wenn ihr Template bereits Anpassung für den Imageslider v1.5 enthalten hat, so entfernen Sie 
       bitte diese Anpassungen vor der Installation! Dazu müssen Sie nur die Anweisungen für die 
       Templateanpassungen aus der Installationsanleitung für die Version v1.5 rückgängig machen.

    2. Falls der admin-Order des Shops unbenannt wurde, dann entsprechnd auch den Ordner admin 
       im Verzeichns shoproot des Moduls vor dem Hochladen ebenfalls entsprechend umbenennen!

    3. Kopieren Sie anschließend einfach alle Dateien in dem Verzeichnis shoproot aus dem Modulpaket 
       MITS_ImageSlider in das Hauptverzeichnis ihrer bestehenden 
       modified eCommerce Shopsoftware 2.x Installation. Es werden dabei keine Dateien überschrieben!

    4. Nachdem sie alle Dateien in den Shop übertragen haben melden sie sich bitte im Shop mit einem 
       gültigen Adminzugang an, der die Berechtigung besitzt auch Systemmodule zu installieren.

    5. Wechseln sie in den Administrationsbereich und rufen sie den Menüpunkt Module -> System-Module auf.

    6. Markieren sie dort den Eintrag 
         MITS ImageSlider © by Hetfield (MerZ IT-SerVice) 
       und klicken sie dann auf der rechten Seite auf den Button Installieren. Das Modul wird nun komplett installiert. 
       Sollten sie von einer früheren Version updaten, erledigt das Moduls die Anpassung der Datenbank komplett für sie.
       Aktivieren sie dazu im Bearbeitungsmodus bei "Modulaktualisierung" die Checkbox für
         Datenbankaktualisierung für den MITS ImageSlider durchführen?
       und klicken sie auf speichern.

    7. Konfigurieren sie nun das Modul nach ihren Wünschen. Die verschiedenen Einstellmöglichkeiten sind im Modul erklärt.

    8. Rufen sie den Menüpunkt Hilfsprogramme -> MITS ImageSlider v2.06 auf. 
       Dort können sie Einträge anlegen, bearbeiten oder löschen.


## 2. Den MITS ImageSlider im Template verfügbar machen

Um den ImageSlider im Template anzuzeigen, gibt es in der neuen Version verschiedene Möglichkeiten.

### Variante 1: Per Smarty-Variable in der index.html des verwendeten Templates

Fügen Sie für den Aufruf des Standard-ImageSlider-Gruppe *MITS_IMAGESLIDER* einfach folgende Smarty-Variable an die gewünschte Stelle in ihrer index.html ein:

    {if isset($MITS_IMAGESLIDER)}{$MITS_IMAGESLIDER}{/if}

Möchten sie z.B. eine andere ImageSlider-Gruppe anzeigen lassen, dann fügen Sie für den Aufruf der anderen ImageSlider-Gruppe (in diesem Beispiel mit dem Gruppennamen *ANDRER_IMAGESLIDER*) einfach folgende Smarty-Variable an die gewünschte Stelle in ihrer index.html ein:

    {if isset($ANDERER_IMAGESLIDER)}{$ANDERER_IMAGESLIDER}{/if}
        
Verwenden Sie das Slider-Plugin "NivoSlider", dann stehen ihnen insgesamt 4 verschieden Themes (Designs) zur Auswahl. 
Folgende 4 Themes stehen ihnen beim NivoSlider zur Verfügung:

  - theme-bar
  - theme-dark
  - theme-default (Standard-Theme, kein besondere Aufruf notwendig)
  - theme-light

Diese können Sie durch Anpassung der Smarty-Variable per replace-Befehl wechseln. 
Im folgenden Beispiel wird das Theme von *theme-default* auf *theme-bar* gewechselt:

    {if isset($MITS_IMAGESLIDER)}
      {$MITS_IMAGESLIDER|replace:'slider-wrapper theme-default':'slider-wrapper theme-bar'}
    {/if}

Hier ein weiteres Beispiel für den Nivo-Slider, dabei wird das Theme von *theme-default* auf *theme-light* gewechselt:

    {if isset($ANDERER_IMAGESLIDER)}
      {$ANDERER_IMAGESLIDER|replace:'slider-wrapper theme-default':'slider-wrapper theme-light'}
    {/if}

Hinweis: Der NivoSlider wird nicht mehr weiterentwickelt und funktioniert nicht mehr in aktuellen Templates mit höheren jQuery-Versionen.

Sie können die Einstellung des ImagSlider-Moduls zur Anzeige start (nur auf der Startseite verfügbar) auch anders lösen. Stellen sie dazu die Anzeige des Imagesliders auf ***general*** um. Anschließend können Sie dann zur Beschränkung der Anzeige auf die Startseite auch folgenden Smarty-Code in der index.html verwenden:

    {if strstr ($smarty.server.PHP_SELF, 'index')}
      {if !strstr ($smarty.server.PHP_SELF, 'checkout')}
        {if $smarty.get.cPath==null and $smarty.get.manufacturers_id==''}
          {if isset($MITS_IMAGESLIDER)}
            {$MITS_IMAGESLIDER|replace:'slider-wrapper theme-default':'slider-wrapper theme-dark'}
          {/if}
        {/if}
      {/if}
    {/if}
        

### Variante 2: Als Smarty-Plugin überall im Template verwenden

Neu seit dem ImageSlider v2.02 ist die Nutzung des ImageSliders als Smarty-Plugin. Damit können Sie den ImageSlider in jeder beliebigen HTML-Template-Datei aufrufen. Voraussetzung dafür ist die Einstellung der Anzeigeart ***general***. 

Der Standardaufruf mit der Gruppe *MITS_IMAGESLIDER* sieht wie folgt aus:

    {getImageSlider slidergroup=mits_imageslider}

Der Aufruf mit einer anderen Gruppe (hier z.B. die ImageSlider-Gruppe *ANDERER_IMAGESLIDER*) sieht wie folgt aus:

    {getImageSlider slidergroup=anderer_imageslider}

Durch die Erweiterung des Aufrufs um den Parameter nivotheme können sie bei der Verwendung des Slider-Plugins "NivoSlider" das Theme wechseln. 
Im folgenden Beispiel wird das Theme von *theme-default* auf *theme-bar* gewechselt:

    {getImageSlider slidergroup=mits_imageslider nivotheme=theme-bar}


Hier ein weiteres Beispiel, diesmal wird das Theme von *theme-default* auf *theme-dark* gewechselt:

    {getImageSlider slidergroup=anderer_imageslider nivotheme=theme-dark}

Der ImageSlider kann seit der Version 2.10 auch als Array in einer Smarty-Variablen zur&uuml;ckgegeben werden f&uuml;r individuelle Darstellungen, die dann per foreach im Template genutzt werden kann. Das sieht wie folgt aus (hier gezeigt als Beispiel f&uuml;r die ImageSlider-Gruppe *ANDERER_IMAGESLIDER*):
      
        {getImageSlider slidergroup=anderer_imageslider get_smarty_array=true}
        <div class="content_slider cf">
          <div class="slider_home">
            {foreach item=slider_data from=$anderer_imageslider}
            <div class="slider_item">
              <a href="{$slider_data.link}" title="{$slider_data.titel}" {$slider_data.target}>
                <picture>
                  <source media="(max-width:600px)" data-srcset="{$slider_data.mobile_bild}">
                  <source media="(max-width:1023px)" data-srcset="{$slider_data.tablet_bild}">
                  <source data-srcset="{$slider_data.haupt_bild}">
                  <img class="lazyload" data-src="{$slider_data.haupt_bild}" alt="{$slider_data.alt}" title="{$slider_data.titel}" />
                </picture>
              </a>
            </div>
            {/foreach}
          </div>
        </div>
      

Folgende Variablen sind für diesen Fall verfügbar:
<ul>
<li><strong>id</strong> (ID des Slidereintrags)</li>
<li><strong>haupt_bild</strong> (Bildadresse vom Hauptbild des Slidereintrags)</li>
<li><strong>tablet_bild</strong> (Bildadresse zur Tablet-Ansicht des Slidereintrags)</li>
<li><strong>mobile_bild</strong> (Bildadresse zur mobilen Ansicht des Slidereintrags)</li>
<li><strong>link</strong> (URL des Slidereintrags)</li>
<li><strong>target</strong> (Zielfenster der URL)</li>
<li><strong>alt</strong> (Alt-Text für Bild)</li>
<li><strong>titel</strong> (Titel für Bild)</li>
<li><strong>text</strong> (Text aus dem Editor)</li>
</ul>

Optional können Sie seit der Version 2.03 auch bei Produkten und Kategorien eine Slidergruppe zuweisen.
Damit können Sie für Produkte und Kategorien einfach eigene Slider erstellen und pflegen ohne viel Aufwand.
Zur Nutzung dieser Funktion müssen Sie allerdings einige Templateanpassungen vornehmen.

Für die Anpassung zur Slideranzeige bei Produkten müssen Sie in den gewünschten Templatevorlagen für ihre Produkte (dazu gehören alle Template-Dateien im Ordner *module/product_info* ihres Templates, z.B. *shoproot/templates/tpl_modified_responsive/module/product_info/product_info_tabs_v1.html*) einfach an gewünschter Stelle folgende Zeile einfügen:

    {if isset($MITS_PRODUCTS_IMAGESLIDER)}{$MITS_PRODUCTS_IMAGESLIDER}{/if}

Für die Anpassung zur Slideranzeige bei Kategorien müssen Sie die Templatevorlagen für ihre Kategorien (dazu gehören alle Template-Dateien im Ordner *module/product_listing* und *module/categorie_listing* ihres Templates, z.B. *shoproot/templates/tpl_modified_responsive/module/product_listing/product_listing_v1.html* oder *shoproot/templates/tpl_modified_responsive/module/categorie_listing/categorie_listing.html*) anpassen.
Dazu einfach in der jeweiligen Datei an gewünschter Stelle folgende Zeile einfügen:

    {if isset($MITS_CATEGORIES_IMAGESLIDER)}{$MITS_CATEGORIES_IMAGESLIDER}{/if}

Für die Anpassung zur Slideranzeige bei Content-Seiten müssen Sie in die Templatevorlage für die Content-Seiten *content.html* im Ordner *module* ihres Templates (z.B. *shoproot/templates/tpl_modified_responsive/module/content.html*) einfach an gewünschter Stelle folgende Zeile einfügen:

    {if isset($MITS_CONTENT_IMAGESLIDER)}{$MITS_CONTENT_IMAGESLIDER}{/if}

## 3. Fertig

<hr />

Wir hoffen, der neue MITS ImageSlider für die modified eCommerce Shopsoftware 2.x gefällt ihnen!
Benötigen sie Unterstützung bei der individuellen Anpassung des Moduls oder haben sie eventuell doch Probleme beim Einbau?
Gerne können sie unseren kostenpflichtigen Support in Anspruch nehmen.
Kontaktieren sie uns einfach unter <a href="https://www.merz-it-service.de/Kontakt.html">info(at)merz-it-service.de</a>

<hr />
<img src="https://www.merz-it-service.de/images/logo.png" alt="MerZ IT-SerVice" title="MerZ IT-SerVice" />

**MerZ IT-SerVice** Nicole Grewe - Am Berndebach 35a - D-57439 Attendorn
Telefon: 0 27 22 - 63 13 63 - Telefax: 0 27 22 - 63 14 00
E-Mail: <a href="https://www.merz-it-service.de/Kontakt.html">Info(at)MerZ-IT-SerVice.de</a> - Internet: <a href="https://www.merz-it-service.de">www.MerZ-IT-SerVice.de</a>

<hr />
