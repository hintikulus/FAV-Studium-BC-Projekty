/********************************************************************
					VLOŽENI PŘEDMĚTU DO INVENTÁŘE
********************************************************************/

INSERT INTO VLASTNI (id_hrace, id_predmetu) VALUES (1,1) ON DUPLICATE KEY
UPDATE pocet = pocet + 1;
