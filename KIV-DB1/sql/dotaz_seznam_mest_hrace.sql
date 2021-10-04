/********************************************************************
						SELEKCE VESNIC HRÁČ
********************************************************************/
SELECT m.nazev as 'Název města', m.p_obyvatel as 'Počet obyvatel',
m.p_vojaku as 'Počet vojenských jednotek', m.u_radnice as 'Úroveň
radnice', m.u_kostel as 'Úroveň kostela', m.u_kasaren as 'Úroveň
kasáren', m.u_fabriky as 'Úroveň fabriky', m.u_hradeb as 'Úroveň hradeb'
from MESTA m where m.id_hrac = 1;
