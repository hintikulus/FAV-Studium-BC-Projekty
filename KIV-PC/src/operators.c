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

#include "operators.h"
/**
 * @brief Pole dostupných operací a konstanta, která udržuje jejich počet.
 */
const int OPERATORS_COUNT = 15;
const calc_oper OPERATORS[] = {
    { "+", sum },       /* Sčítání. */
    { "-", sub },       /* Odčítání. */
    { "*", mul },       /* Násobení. */
    { "/", divide },    /* Dělení. */
    { "=", equal },     /* Porovnání rovnosti. */
    { "/=", nequal },   /* Porovnání nerovnosti. */
    { "<", smt},        /* Porovnání menší než. */
    { ">", grt},        /* Porovnání větší než. */
    { "<=", smte},      /* Porovnání menší nebo rovno než. */
    { ">=", grte},      /* Porovnání větší nebo rovno než. */
    { "SET", set},      /* Nastavení hodnoty proměnné. */
    { "QUOTE", quote},  /* Nevyhodnocení výrazu. */
    { "LIST", olist},   /* Výčet. */
    { "CAR", car},      /* První prvek. */
    { "CDR", cdr}       /* Pole bez prvního prvku. */
};

/**
 * @brief Funkce získá handler na základě vloženého operátoru
 * 
 * @param operator[] řetězec značky operátoru
 * @return fcalc odkaz na funkci
 */
fcalc get_handler(char operator[]) {
    int i;


    for (i = 0; i < OPERATORS_COUNT; ++i) {
        if (!strcmp(OPERATORS[i].operator, operator)) {
            return OPERATORS[i].handler;
        }
    }

    return NULL;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * sečte je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int sum(stack *q, stack *s, list *vars) {
    int result;
    int a;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &result)) {
        str_to_number(&result, value);
    }


    free(value);
    while (next_operator(q, &value)) {
        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        if (!list_get(vars, value, &a)) {
            str_to_number(&a, value);
        }
        result += a;
        free(value);

    }

    if (!number_to_stack(result, s)) {
        return 0;
    }


    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * odečte je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int sub(stack *q, stack *s, list *vars) {
    int result;
    int a;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &result)) {
        str_to_number(&result, value);
    }


    free(value);
    while (next_operator(q, &value)) {
        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        
        if (!list_get(vars, value, &a)) {
            str_to_number(&a, value);
        }
        result -= a;
        free(value);

    }

    if (!number_to_stack(result, s)) {
        return 0;
    }


    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vynásobí je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int mul(stack *q, stack *s, list *vars) {
    int result;
    int a;
    char *value = "";



    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &result)) {
        str_to_number(&result, value);
    }

    free(value);
    while (next_operator(q, &value)) {
        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }

        if (!list_get(vars, value, &a)) {
            str_to_number(&a, value);
        }
        result *= a;
        free(value);
    }

    if (!number_to_stack(result, s)) {
        return 0;
    }


    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vydělí je a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int divide(stack *q, stack *s, list *vars) {
    
    int result;
    int a;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &result)) {
        str_to_number(&result, value);
    }
    free(value);
    while (next_operator(q, &value)) {
        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        if (!list_get(vars, value, &a)) {
            str_to_number(&a, value);
        }
        result /= a;
        
        free(value);
    }

    if (!number_to_stack(result, s)) {
        return 0;
    }

    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na rovnost a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int equal(stack *q, stack *s, list *vars) {
    int a, b;
    int result = 1;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }
    
    if (!list_get(vars, value, &a)) {
        str_to_number(&a, value);
    }

    free(value);
    while (next_operator(q, &value)) {

        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }
        
        if (a != b) {
            result = 0;
            free(value);
            break;
        }

        a = b;
        free(value);
    }

    if (result) {
        insert_T(s);
    } else {
        insert_NIL(s);
    }

    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na nerovnost a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int nequal(stack *q, stack *s, list *vars) {
    int a, b;
    int result = 1;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &a)) {
        str_to_number(&a, value);
    }

    free(value);
    while (next_operator(q, &value)) {

        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }
        
        if (a == b) {
            result = 0;
            free(value);
            break;
        }

        a = b;
        free(value);
    }

    if (result) {
        insert_T(s);
    } else {
        insert_NIL(s);
    }

    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na menší než a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int smt(stack *q, stack *s, list *vars) {
    int a, b;
    int result = 1;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }
    
    if (!list_get(vars, value, &a)) {
        str_to_number(&a, value);
    }
    
    free(value);
    while (next_operator(q, &value)) {

        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }
        
        if (a >= b) {
            result = 0;
            free(value);
            break;
        }

        a = b;
        
        free(value);
    }

    if (result) {
        insert_T(s);
    } else {
        insert_NIL(s);
    }

    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na větší než a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int grt(stack *q, stack *s, list *vars) {
    int a, b;
    int result = 1;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &a)) {
        str_to_number(&a, value);
    }

    
    free(value);
    while (next_operator(q, &value)) {

        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }
        
        if (a <= b) {
            result = 0;
            free(value);
            break;
        }

        a = b;

        free(value);
    }

    if (result) {
        insert_T(s);
    } else {
        insert_NIL(s);
    }

    return EXIT_SUCCESS;

}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na menší než nebo rovno a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int smte(stack *q, stack *s, list *vars) {
    int a, b;
    int result = 1;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &a)) {
        str_to_number(&a, value);
    }
    
    free(value);

    while (next_operator(q, &value)) {

        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }
        
        if (a > b) {
            result = 0;
            free(value);
            break;
        }

        a = b;
        
        free(value);
    }

    if (result) {
        insert_T(s);
    } else {
        insert_NIL(s);
    }

    return EXIT_SUCCESS;
}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * porovná je na větší než nebo rovno a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int grte(stack *q, stack *s, list *vars) {
    int a, b;
    int result = 1;
    char *value = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (!list_get(vars, value, &a)) {
        str_to_number(&a, value);
    }

    free(value);
    while (next_operator(q, &value)) {

        if (!strcmp(value, "NIL")) {
            printf("CHYBA: Hodnota NIL není číslo.\n");
            free(value);
            return 0;
        }
        
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }
        
        if (a < b) {
            result = 0;
            free(value);
            break;
        }

        a = b;

        free(value);
    }

    if (result) {
        insert_T(s);
    } else {
        insert_NIL(s);
    }

    return EXIT_SUCCESS;

}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * podle nich nastaví příslušnou proměnnou a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int set(stack *q, stack *s, list *vars) {
    int b;
    char *value = "", *key = "";

    if (!q || !s || !vars) {
        return 0;
    }

    if (!next_operator(q, &key)) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        free(key);
        return 0;        
    }

    if (!list_get(vars, value, &b)) {
        str_to_number(&b, value);
    }

    if (list_add(vars, key, &b)) {
        free(key);
        free(value);
        number_to_stack(b, s);
        return EXIT_SUCCESS;
    }

    free(key);
    free(value);
    return 0;

}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vyhodnotí je podle QUOTE funkce a výsledek vrátí do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int quote(stack *q, stack *s, list *vars) {
    char znak = ' ';

    if (!q || !s || !vars) {
        return 0;
    }


    while (stack_item_count(q)) {
        if (!stack_pop(q, &znak)) {
            break;
        }

        if (!stack_push(s, &znak)) {
            break;
        }
    }
    return 0;

}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vykoná funkci LIST a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int olist(stack *q, stack *s, list *vars) {
    int b;
    char *value = "";
    char znak = ' ';
    char brackets[2];
    brackets[0] = LIST_BRACKET_OPEN;
    brackets[1] = LIST_BRACKET_CLOSE;

    if (!q || !s || !vars) {
        return 0;
    }


    if (!stack_push(s, &(brackets[0]))) {
        return 0;
    }

    if (!next_operator(q, &value)) {
        return 0;
    }

    if (!strcmp(value, "NIL")) {
        printf("CHYBA: Hodnota NIL není číslo.\n");
        free(value);
        return 0;
    }

    if (value[0] != LIST_BRACKET_OPEN) {
        if (!list_get(vars, value, &b)) {
            str_to_number(&b, value);
        }

        if (!number_to_stack(b, s)) {
            return 0;
        }
    } else {
        str_to_stack(s, value);
    }
    free(value);
    
    while (next_operator(q, &value)) {
        stack_push(s, &znak);
        if (value[0] != LIST_BRACKET_OPEN) {
            if (!list_get(vars, value, &b)) {
                str_to_number(&b, value);
            }

            if (!number_to_stack(b, s)) {
                return 0;
            }
        } else {
            str_to_stack(s, value);
        }

        free(value);
    }
    

    
    if (!stack_push(s, &(brackets[1]))) {
        return 0;
    }

    return EXIT_SUCCESS;

}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * a vrátí jako výsledek první prvek pole. Výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int car(stack *q, stack *s, list *vars) {
    int i;
    char *value = "";
    char temp;
    int str_len;

    if (!q || !s || !vars) {
        return 0;
    }

    stack_pop(q, &temp);

    if (!next_operator(q, &value)) {
        return 0;
    }
    
    str_len = strlen(value);

    if (value[0] == LIST_BRACKET_OPEN) str_len--;

    for (i = 0; i < str_len; i++) {
        stack_push(s, &(value[i]));
    }
    
    free(value);

    return EXIT_SUCCESS;

}

/**
 * @brief Funkce vezme všechny prvky z q zásobníku, 
 * vytvoří podle nich seznam bez prvního prvku a výsledek zapíše do zásobníku s.
 * 
 * @param q odkaz na pomocný zásobník
 * @param s odkaz na hlavní zásobník
 * @param vars odkaz na spojový seznam s proměnnými
 * @return int informace o úspěšnosti operace
 */
int cdr(stack *q, stack *s, list *vars) {
    char *value = "";
    char znak = ' ';
    char temp;
    char brackets[2];
    brackets[0] = LIST_BRACKET_OPEN;
    brackets[1] = LIST_BRACKET_CLOSE;


    if (!q || !s || !vars) {
        return 0;
    }

    stack_pop(q, &temp);

    if (!next_operator(q, &value)) {
        return 0;
    }
    free(value);

    if (stack_item_count(q)) {
        if (!stack_push(s, &(brackets[0]))) {
            return 0;
        }
    }

    while (stack_item_count(q)) {
        if (!stack_pop(q, &znak)) {
            break;
        }

        if (!stack_push(s, &znak)) {
            break;
        }
    }

    return EXIT_SUCCESS;

}