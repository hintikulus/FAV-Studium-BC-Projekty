/********************************************************************
				 VÝPIS SEZNAMU PŘIHLÁŠENÍ UŽIVATELE
********************************************************************/
SELECT p.d_prihlaseni as 'Datum a čas přihlášení', ip.ip as 'Adresa
připojení' FROM PRIHLASIL p inner join IP_ADRESY ip on p.id_adresa =
ip.id_ip where p.id_uzivatel = 1;