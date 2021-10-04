
/**
 * Třída pro klasifikační třídy projektu. Obsahuje všechny třídy, do kterých lze klasifikovat.
 * Každá klasifikační třída má zadanou i číselnou reprezentaci,
 * která v aplikaci zároveň slouží jako název složky ve které se hledají obrázky pro danou třídu.
 * @author hintik
 *
 */
public enum Class {

	CLASS_0(0),
	CLASS_1(1),
	CLASS_2(2),
	CLASS_3(3),
	CLASS_4(4),
	CLASS_5(5),
	CLASS_6(6),
	CLASS_7(7),
	CLASS_8(8),
	CLASS_9(9);

	int value;
	
	Class(int value) {
		this.value = value;
	}
}
