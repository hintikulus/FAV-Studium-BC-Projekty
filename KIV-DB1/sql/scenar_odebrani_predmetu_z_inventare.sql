/********************************************************************
				  ODSTRANĚNÍ PŘEDMĚTU Z INVENTÁŘE
********************************************************************/
DELETE FROM VLASTNI WHERE id_hrace = 1 AND id_predmetu = 1 AND pocet = 1;

/********************************************************************
			 ODEBRÁNÍ JEDNOHO KUSU PŘEDMĚTU Z INVENTÁŘE
********************************************************************/
UPDATE VLASTNI SET pocet = pocet - 1 WHERE id_hrace = 1 AND id_predmetu = 1 AND pocet > 1;
