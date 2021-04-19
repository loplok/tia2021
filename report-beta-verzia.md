<h2>Patrik Černý, beta-verzia report</h2>

<p>Socialna siet per milovnikov knih</p>

Link sa nachadza tu: http://www.st.fmph.uniba.sk/~cerny33/tia/login_menu.php

Aké features sú už implementované, rozpracované, neimplmentované vobec?
<p>Doteraz sa mi podarilo implementovat prihlasovanie a registraciu, vlastnu kniznicu pre usera rozdelenu podla kategorii, skupiny pre pouzivatelov, moznost
pridavania prispevku v skupinach. Na profile si kazdy pouzivatel vie prezriet prispevky zo skupin, taktiez je velmi blizko ku dokonceniu search bar, ktory je necakane
najzlozitejsi komponent celej stranky. Bude sa vyuzivat hlavne na hladanie skupin a pouzivatelov, nakolko sa mi nepodarilo zohnat rozumnu kniznu databazu. 
</p>

Koľko hodín čistého času ste venovali projektu doteraz?
ak by som to mal odhadnut, bude to okolo 80 hodin cisteho casu, je to nafuknute hlavne horsim navrhom databazy, ktora sa teraz uz tazko upravuje a zbytocne ma spomaluje 
v rieseni jednoduchsich features. 

Aké sú Vaše plány na ďalšie obdobie? Aký je časový plán?
kedze doteraz bola uprednostena v kazdom weekly reporte funkcionalita, postupne by som zacal pracovat aj na dizajne a vyzera stranky, doteraz som sa tym takmer vobec 
nezaoberal. Taktiez vela funkcionalit potrebuje osetrit krajne pripady, aby nenastavali problemy. 

Casovy plan: 
19-25.4 mam v plane implementovat komentovanie a hodnotenie prispevkov a zacat s dizajnom, + implementovat pripadne nedostatky z beta verzie, ktore dostanem ako 
feedback. Ako vedlajsia uloha by bolo este dokoncenie search baru, cim bude funkcionalita takmer hotova a ostane len dizajn.
26.-2.5 je v plane venovat sa krajnym pripadom features, osetrit vsetky funkcie a pod., a venovat sa cisto dizajnu stranky a implementovat banovanie a management skupin pre ich vlastnikov/adminov
3.-9.5 priprava na finalnu verziu, dizajnove upravy, rezerva pre pripadne pripomienky alebo pridanie inych features ktore boli vyhodene

<p>Tento tyzden som zacal velkou upravou db, migroval som hosting na cluster Davinci z DigitalOceanu a spravil uvodnu stranu, ktora podporuje prihlasenie/registraciu. Aktualne je na  DigitalOcean len databaza, PHP sa nachadza Davinci studentskom clusteri. Link na davinci: http://www.st.fmph.uniba.sk/~cerny33/tia/ </p>

S čím ste mali problémy?
Najvacsie problemy boli ako som spominal urcite z databazou, zly navrch mi zbytocne stazuje implementacie, okrem toho asi len to ze to je relativne vela kodu tak dlhsie trva
pisat ho, aj ked nie je logicky narocny, da to mentalne zabrat.
