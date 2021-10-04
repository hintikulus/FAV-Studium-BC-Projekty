/********************************************************************
		   VLOŽENI NOVÉ IP ADRESY (POKUD JI JIŽ NEOBSAHUJE)
********************************************************************/
INSERT INTO IP_ADRESY (ip) VALUES ('192.168.255.1') ON DUPLICATE KEY
UPDATE ip= '192.168.255.1';

/********************************************************************
    ZAPSÁNÍ INFORMACE O PŘIHLÁŠENÍ V DANÉM ČASE A Z DANÉ IP ADRESY
********************************************************************/
INSERT INTO PRIHLASIL (id_uzivatel, id_adresa) VALUES (1, (SELECT id_ip 
from IP_ADRESY where ip = '192.168.4.32'));