/**
 * @file stack.h
 * @author Jan Hinterholzinger
 * @brief Soubor funkcí pro práci se zásobníkovou strukturou
 * @version 0.1
 * @date 2021-01-15
 * 
 * @copyright Copyright (c) 2021
 * 
 */

#include "stack.h"

/**
 * @brief Funkce pro vytvoření nového zásobníku.
 * @param size Maximální kapacita zásobníku.
 * @param item_size Velikost vkládaných prvků.
 * @return stack* Pointer na nový zásobník (v případě neúspěchu vrací NULL)
 */
stack *stack_create(uns_int size, uns_int item_size) {
    stack *new = NULL;

    if (!size || !item_size) {
        return NULL;
    }

    new = (stack *) malloc(sizeof(stack));

    if (!new) {
        return NULL;
    }

    new->size = size;
    new->item_size = item_size;
    new->sp = -1;

    new->items = malloc(size * item_size);

    if (!new->items) {
        free(new);
        return NULL;
    }

    return new;
}

/**
 * @brief Funkce pro vložení nového prvku na vrchol zásobníku.
 * @param s Zásobník, do kterého bude vložen nový záznam.
 * @param item Pointer na vkládaný prvek.
 * @return Informace o úspěchu operace (1 = úspěch; 0 = selhání)
 */
int stack_push(stack *s, void *item) {

    if (!s || !s->items || !item) {
        return 0;
    }

    if (s->sp >= (int) s->size - 1) {
        return 0;
    }

    s->sp++;

    memcpy((char *) s->items + s->sp * s->item_size, item, s->item_size);

    return 1;
    
}

/**
 * @brief Funkce pro odebrání prvku z vrcholu zásobníku.
 * @param s Zásobník, ze kterého bude prvek odebrán.
 * @param item Pointer na místo, kam bude odebraný prvek zkopírován.
 * @return Informace o úspěchu operace (1 = úspěch; 0 = selhání)
 */
int stack_pop(stack *s, void *item) {
    
    if (stack_peek(s, item)) {
        
        s->sp--;
        
        return 1;
    } else {
        
        return 0;
    }
}

/**
 * @brief Funkce pro získání prvku z vrcholu zásobníku.
 * @param s Zásobník, jehož poslední přidaný prvek pozorujeme.
 * @param item Pointer na místo, kam se přečtený prvek zapíše.
 * @return Informace o úspěchu operace (1 = úspěch; 0 = selhání)
 */
int stack_peek(stack *s, void *item) {
    
    if (!s || !s->items || s->sp < 0) {
        return 0;
    }

    memcpy(item, (char *) s->items + s->sp * s->item_size, s->item_size);

    return 1;
}

/**
 * @brief Funkce vrátí počet prvků v zásobníku.
 * @param s Ukazatel na zásobník.
 * @return uint Počet prvků v zásobníku.
 */
uns_int stack_item_count(stack *s) {
    if (!s || !s->items) {
        return 0;
    }

    return (uns_int) (s->sp + 1);
}

/**
 * @brief Funkce pro uvolnění zásobníkové struktury z paměti.
 * @param ss Ukazatel na ukazatel na zásobník, který bude uvolněn.
 */
void stack_free(stack **s) {
    if (!s || !*s) {
        return;
    }

    free((*s)->items);
    free(*s);
    *s = NULL;

}