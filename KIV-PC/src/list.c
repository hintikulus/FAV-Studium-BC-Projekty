/**
 * @file list.h
 * @author Jan Hinterholzinger
 * @brief Soubor funkcí pro práci se strukturou spojového seznamu
 * @version 0.1
 * @date 2021-01-15
 * 
 * @copyright Copyright (c) 2021
 * 
 */

#include "list.h"

/**
 * @brief Funkce pro vytvoření spojového seznamu.
 * 
 * @return list* pointer na strukturu spojového seznamu (v případě neúspěchu vrací NULL)
 */
list *list_create() {
    list *new = NULL;

    new = (list *) malloc(sizeof(list));

    if (!new) {
        return NULL;
    }

    new->size = 0;
    new->first = NULL;

    return new;
}

/**
 * @brief Vložení cisla do seznamu
 * 
 * @param l pointer na strukturu seznamu
 * @param key retezec jako nazev promenne
 * @param value pointer na cele cislo vkladaneho cisla
 * @return int informace o uspesnosti operace
 */
int list_add(list *l, char *key, int *value) {
    list_node *n = NULL;
    char *nkey = NULL;
    int key_len = 0;
    int tempValue;

    if (!l || !key || !value) {
        return 0;
    }

    key_len = strlen(key);

    if (!key_len) {
        return 0;
    }

    /**
     * @brief Pokud zatím nebyla nastavena
     * 
     */
    if (!list_get(l, key, &tempValue)) {

        nkey = (char *) malloc(sizeof(char) * (key_len + 1));

        if (!nkey) {
            return 0;
        }

        strcpy(nkey, key);

        n = (list_node *) malloc(sizeof(list_node));

        if (!n) {
            free(nkey);
        }

        n->key = nkey;
        n->value = *value;
        n->next = NULL;

        if (!l->first) {
            l->first = n;
        } else {
            n->next = l->first;
            l->first = n;
        }

        l->size++;
    } else {
        /**
         * @brief Pokud je proměnná již nastavena
         * 
         */
        if (!l->first) {
        return 0;
        }

        n = l->first;

        if (!n->key) {
            return 0;
        }

        if (!strcmp(n->key, key)) {
            n->value = *value;
            return 1;
        }

        while (n->next) {
            n = n->next;
            
            if (!strcmp(n->key, key)) {
                n->value = *value;
                return 1;
            }
        }

    }
    return 1;
}

/**
 * @brief Ziskani prvku ze seznamu
 * 
 * @param l pointer na strukturu seznamu
 * @param key retezec nazvu hledane promenne
 * @param value pointer na cislo pro nalezeny vysledek
 * @return int informace o uspesnosti operace
 */
int list_get(list *l, char *key, int *value) {
    list_node *n = NULL;

    if (!l || !key || !value) {
        return 0;
    }

    if (!l->first) {
        return 0;
    }

    n = l->first;

    if (!n->key) {
        return 0;
    }

    if (!strcmp(n->key, key)) {
        *value = n->value;
        return 1;
    }

    while (n->next) {
        n = n->next;
        
        if (!strcmp(n->key, key)) {
            *value = n->value;
            return 1;
        }
    }

    return 0;
}

/**
 * @brief Uvolnění alokované paměti seznamem
 * 
 * @param l pointer na pointer na strukturu seznamu
 */
void list_free(list **l) {
    list_node *n, *next;

    if (!l || !*l) {
        return;
    }

    if (!((*l)->first)) {
        free(*l);
        return;
    }
    
    n = (*l)->first;

    while (n->next) {
        next = n->next;
        free(n->key);
        free(n);
        n = next;
    }
    
    free(n->key);
    free(n);
    
    free(*l);
    return;
}