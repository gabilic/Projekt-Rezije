<!DOCTYPE html>
<html>
    <head>
        <title>{$naslov}</title>
        <meta charset="UTF-8">
        <meta name="author" content="Gabriel Ilić">
        <meta name="keywords" content="WebDiP, projekt, režije, HTML, CSS, Javascript, PHP">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Sustav koji omogućava korisnicima pregled računa svojih režija">
        <link href="css/dizajn.css" rel="stylesheet" type="text/css" media="screen">
    </head>
    <body>
        <header>
            <h1>{$naslov}</h1>
            <p>Projekt režije</p>
            <div id="button_container">
                {if isset($smarty.session.prijava)}
                    {"<a href='odjava.php'><span class='prij_reg_odj'>Odjava</span></a>"}
                {else}
                    {"<a href='prijava.php'><span class='prij_reg_odj'>Prijava</span></a>"}
                    {"<a href='registracija.php'><span class='prij_reg_odj'>Registracija</span></a>"}
                {/if}
            </div>
        </header>
        <div id="container">
            <nav>
                <div id="nav">
                    <ul>
                        <li><a href="index.php">Početna</a></li>
                        <li><a href="privatno/korisnici.php">Ispis korisnika</a></li>
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='virtualno_vrijeme.php'>Virtualno vrijeme</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='dnevnik.php'>Dnevnik</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='statistika.php'>Statistika</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='rad_s_korisnicima.php'>Rad s korisnicima</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='konfiguracija.php'>Konfiguracija sustava</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='ustanove.php'>Kreiranje ustanova</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator"}
                                {"<li><a href='adrese_poslovnica.php'>Definiranje adresa poslovnica</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='ocitavanje.php'>Kategorije i cijena usluge očitavanja</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='rezervacija.php'>Rezervacije očitavanja</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='racun.php'>Izdavanje računa</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='zalba.php'>Popis žalbi</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik"}
                                {"<li><a href='aplikativna_statistika.php'>Aplikativna statistika</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik" || $smarty.session.prijava.1 === "potrošač"}
                                {"<li><a href='korisnik_rezervacija.php'>Rezervacija očitavanja potrošnje</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik" || $smarty.session.prijava.1 === "potrošač"}
                                {"<li><a href='korisnik_racuni.php'>Prikaz računa</a></li>"}
                            {/if}
                        {/if}
                        {if isset($smarty.session.prijava)}
                            {if $smarty.session.prijava.1 === "administrator" || $smarty.session.prijava.1 === "zaposlenik" || $smarty.session.prijava.1 === "potrošač"}
                                {"<li><a href='korisnik_zalba.php'>Podnošenje žalbe</a></li>"}
                            {/if}
                        {/if}
                        <li><a href="neregistrirani_korisnik.php">Popis ustanova</a></li>
                        <li><a href="dokumentacija.html">Dokumentacija</a></li>
                        <li><a href="o_autoru.html">O autoru</a></li>
                    </ul>
                </div>
            </nav>
            <section>
                <div class="blok1">
                    <h2>Izdavanje računa</h2>
                    <form>
                        <label>Datum i vrijeme: </label>
                        <input id="datum_i_vrijeme" disabled><br><br>
                        <label>Naziv usluge očitavanja: </label>
                        <input id="usluga" disabled><br><br>
                        <label>Cijena usluge očitavanja: </label>
                        <input id="cijena" disabled><br><br>
                        <label>Očitano stanje: </label>
                        <input id="stanje" disabled><br><br>
                        <label>Potrošnja: </label>
                        <input id="potrosnja" disabled><br><br>
                        <label>Ime djelatnika: </label>
                        <input id="djelatnik" disabled><br><br>
                        <label>Ime korisnika: </label>
                        <input id="korisnik" disabled><br><br>
                        <label>Broj rezervacije: </label>
                        <select id="rezervacije">
                            <option value="-1">Rezervacija</option>
                            <option value="0">===========</option>
                        </select><br><br>
                        <button type="button" id="spremi">Izdaj račun</button>
                        <button type="button" id="ispis">Ispiši račune</button><br><br>
                    </form><br>
                    <div id="poruka"></div>
                </div>
            </section>
        </div>
        <footer>
            <address>Kontakt: <a href="mailto:gabilic@foi.hr">Gabriel Ilić</a></address>
            <p>&copy; 2016 G.Ilić</p>
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/stranicenje.js"></script>
        <script type="text/javascript" src="js/pretrazivanje.js"></script>
        <script type="text/javascript" src="js/racun.js"></script>
    </body>
</html>