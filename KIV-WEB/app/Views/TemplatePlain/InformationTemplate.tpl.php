<?php

global $tplData;

?>

<div class="container">
    <h3 class="h3"><?= $tplData['title']; ?></h3>
    <p>Webové stránky konference TechMasters. Web slouží pro publikaci a případné schvalování vědeckých článků.</p>
    <h4 class="h4">Vytvoření příspěvku</h4>
    <p>Na této webové aplikaci se publikují články autorů této konference. Pokud chcete přispět svým článkem
        registrujte se a zvolte možnost vytvoření nového příspěvku.</p>
    <p>Publikování nového článku je snadné. Stačí vyplnit titulek článku, abstrakt (úvod) článku a nahrát PDF soubor
        s kompletním článkem.</p>
    <h4 class="h4">Schvalovací proces</h4>
    <p>Po vytvoření nového příspěvku se článek dostává do schvalovacího procesu. Článek tedy není publikován dokud
        není potřebně ohodnocen a na  závěr schválen. Článek schvalují vybraní recenzenti, kterým byl tento článek
        přiřazen administrátorem. Recenze se skládá ze slovního hodnocení, kde recenzent obhajuje proč dal takové hodnocení,
        a číselné hodnocení v těchto kategorií: relevance, kvalita dat, styl textu, zdroje. Všechny tyto aspekty se
        hodnotí na stupnici 0-10 (včetně; 0 = nejhorší, 10 = nejlepší).</p>
    <p>Po dostatečném orecenzování (zpravidla minimálně 3 recenze) je článek postoupen ke schvalovacímu procesu
        k administrátorovi. Administrátor se na základě recenzí recenzentů rozhodne, zda článek bude schválen, a to tedy
    publikovám pro všechny návštěvníky, nebo neschválen, v takovém případě zůstane článek pro veřejnost nepřístupný,
    ale autor stále může číst recenze pro napravení chyb. Administrátor taktéž může kdykoli usoudit, že příspěvek není
    hodný k publikování na této stránce a může článek trvale odstranit (společně se všemi recenzemi k tomuto článku).</p>
</div>
