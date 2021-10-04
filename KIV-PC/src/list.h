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

#ifndef LIST_H
#define LIST_H

#include <stdlib.h>
#include <stdio.h>
#include <string.h>

/**
 * @brief Definice struktury prvku 
 * spojoveho seznamu
 * 
 */
typedef struct _struct_list_node {
    char *key;
    int value;
    struct _struct_list_node *next;
} list_node;

/**
 * @brief Definice struktury spojoveho seznamu
 * 
 */
typedef struct _struct_list {
    int size;
    struct _struct_list_node *first;
} list;

/**
 * @brief Funkce pro vytvoření spojového seznamu.
 * 
 * @return list* pointer na strukturu spojového seznamu
 */
list *list_create();

/**
 * @brief Vložení cisla do seznamu
 * 
 * @param l pointer na strukturu seznamu
 * @param key retezec jako nazev promenne
 * @param value pointer na cele cislo vkladaneho cisla
 * @return int informace o uspesnosti operace
 */
int list_add(list *l, char *key, int *value);

/**
 * @brief Ziskani prvku ze seznamu
 * 
 * @param l pointer na strukturu seznamu
 * @param key retezec nazvu hledane promenne
 * @param value pointer na cislo pro nalezeny vysledek
 * @return int informace o uspesnosti operace
 */
int list_get(list *l, char *key, int *value);

/**
 * @brief Uvolnění alokované paměti seznamem
 * 
 * @param l pointer na pointer na strukturu seznamu
 */
void list_free(list **l);

#endif