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

#ifndef STACK_H
#define STACK_H

#include <stdlib.h>
#include <stdio.h>
#include <string.h>

/* Definice neznaménkového celého číslo, pro zkrácení zápisu */
typedef unsigned int uns_int;

/**
 * @brief Definice zásobníkové struktury s možností vložení libovolně velkého prvku.
 */
typedef struct _struct {
    uns_int size;
    uns_int item_size;
    void *items;
    int sp;
} stack;

/**
 * @brief Funkce pro vytvoření nového zásobníku.
 * @param size Maximální kapacita zásobníku.
 * @param item_size Velikost vkládaných prvků.
 * @return stack* Pointer na nový zásobník (v případě neúspěchu vrací NULL)
 */
stack *stack_create(uns_int size, uns_int item_size);

/**
 * @brief Funkce pro vložení nového prvku na vrchol zásobníku.
 * @param s Zásobník, do kterého bude vložen nový záznam.
 * @param item Pointer na vkládaný prvek.
 * @return Informace o úspěchu operace (1 = úspěch; 0 = selhání)
 */
int stack_push(stack *s, void *item);

/**
 * @brief Funkce pro odebrání prvku z vrcholu zásobníku.
 * @param s Zásobník, ze kterého bude prvek odebrán.
 * @param item Pointer na místo, kam bude odebraný prvek zkopírován.
 * @return Informace o úspěchu operace (1 = úspěch; 0 = selhání)
 */
int stack_pop(stack *s, void *item);

/**
 * @brief Funkce pro získání prvku z vrcholu zásobníku.
 * @param s Zásobník, jehož poslední přidaný prvek pozorujeme.
 * @param item Pointer na místo, kam se přečtený prvek zapíše.
 * @return Informace o úspěchu operace (1 = úspěch; 0 = selhání)
 */
int stack_peek(stack *s, void *item);

/**
 * @brief Funkce vrátí počet prvků v zásobníku.
 * @param s Ukazatel na zásobník.
 * @return uint Počet prvků v zásobníku.
 */
uns_int stack_item_count(stack *s);

/**
 * @brief Funkce pro uvolnění zásobníkové struktury z paměti.
 * @param ss Ukazatel na ukazatel na zásobník, který bude uvolněn.
 */
void stack_free(stack **s);

#endif