/**
 * @file operators.h 
 * @author Jan Hinterholzinger
 * @brief Soubor funkcí operací pro vyhodnocování výrazu
 * @version 0.1
 * @date 2021-01-15
 * 
 * @copyright Copyright (c) 2021
 * 
 */

#ifndef OPERATORS_H
#define OPERATORS_H

#include <stdlib.h>
#include <stdio.h>
#include <string.h>

#include "loader.h"
#include "stack.h"
#include "list.h"

/**
 * @brief Definice prototypu funkce
 * 
 */
typedef int(*fcalc)(stack *, stack *, list *);

/**
 * @brief Definice struktury operátoru
 * 
 */
typedef struct _calc_oper {
    char *operator;              /** Řetězec, kterým je operace popsána. */
    fcalc handler;              /** Přidružená aritmetická operace. */
} calc_oper;

/**
 * @brief Funkce získá handler na základě vloženého operátoru
 * 
 * @param operator[] řetězec značky operátoru
 * @return fcalc odkaz na funkci
 */
fcalc get_handler(char operator[]);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * sečte je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int sum(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * odečte je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int sub(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vynásobí je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int mul(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vydělí je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int divide(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na rovnost a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int equal(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na nerovnost a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int nequal(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na menší než a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int smt(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na větší než a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int grt(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na menší než nebo rovno a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int smte(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na větší než nebo rovno a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int grte(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * podle nich nastaví příslušnou proměnnou a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int set(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vyhodnotí je podle QUOTE funkce a výsledek vrátí do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int quote(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vykoná funkci LIST a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int olist(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * a vrátí jako výsledek první prvek pole. Výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int car(stack *q, stack *s, list *vars);

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vytvoří podle nich seznam bez prvního prvku a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int cdr(stack *q, stack *s, list *vars);


#endif