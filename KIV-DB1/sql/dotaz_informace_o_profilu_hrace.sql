/********************************************************************
				ZOBRAZENÍ INFORMACÍ O PROFILU HRÁČE
********************************************************************/
SELECT jmeno as 'Přezdívka', a.nazev as "Aliance", h.zkusenosti as 'Zkušenostní body',
(SELECT COUNT(*) FROM MESTA m where id_hrac = 1) as "Počet měst",
(SELECT SUM(p_obyvatel) FROM MESTA m where id_hrac = 1) as "Počet poddaných",
(SELECT COUNT(*) FROM PRISPEVKY m where id_autor = 1) as "Počet příspěvků"
from HRACI h inner join ALIANCE a on a.id = h.id_aliance 
where h.id = 1