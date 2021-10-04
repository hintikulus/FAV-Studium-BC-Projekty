/**
 * @file loader.h
 * @author Jan Hinterholzinger
 * @brief Soubor funkcí pro práci s načítáním a zpracováním dat
 * @version 0.1
 * @date 2021-01-15
 * 
 * @copyright Copyright (c) 2021
 * 
 */

#include <stdlib.h>
#include <stdio.h>
#include <string.h>

#include "stack.h"
#include "operators.h"
/**
 * @brief Konstanty závorek obklopující běžný výraz
 * 
 */
#define EXPRESSION_BRACKET_OPEN '('
#define EXPRESSION_BRACKET_CLOSE ')'
/**
 * @brief Znak zacinajici QUOTE vyrazu
 * 
 */
#define QUOTE_SHORTCUT '\''
/**
 * @brief Konstanty zavorek obklopující QUOTE výraz
 * 
 */
#define QUOTE_BRACKET_OPEN '['
#define QUOTE_BRACKET_CLOSE ']'

/**
 * @brief Konstanty závorek obklopující seznam položek LIST
 * 
 */
#define LIST_BRACKET_OPEN '{'
#define LIST_BRACKET_CLOSE '}'

/**
 * @brief Metoda našte další část řetězce pro vyhodnocení. Například pro načtení operátoru
 * nebo operandu.
 * 
 * @param s pointer na zásobník
 * @param result výsledná část řetěce
 * @return int informace o úspěšnosti
 */
int next_operator(stack *s, char **result);

/**
 * @brief Funkce získá znak z řetězce od zadané pozice do zadané pozice
 * 
 * @param input vstupní řetěz
 * @param output pointer na výsledný znak na zadané pozici v řetězci
 * @param index pozice v řetězci, ze které chceme získat znak
 * @param to konečný index části řetězce. Jakmile index dosáhne hodnoty to,
 *          funkce vrátí signál, že je na konci části řetězce
 * @return int informace, zda je funkce na konci části řetězce
 */
int nextValue(char *input, char *output, int *index, int to);

/**
 * @brief Funkce převádí číslo na řetězec
 * 
 * @param number převáděné číslo, které se má převést na řetězec
 * @param mezivýsledek výsledný řetězec obsahující zadané číslo
 * @return int 
 */
int number_to_str(int number, char mezivysledek[]);

/**
 * @brief Funkce převádí řetězec na celé číslo 
 * 
 * @param number pointer na místo, kam se uloží výsledné celé číslo
 * @param str řetězec ve kterém se se má vyskytovat 
 */
void str_to_number(int *number, char str[]);

/**
 * @brief Funkce vlozí veškeré znaky čísla (převedeného na řetězec) do zásobníku
 * 
 * @param number vkládané číslo
 * @param s zásobník, do kterého se číslo vloží
 * @return int informace o úspěšnosti vkládání
 */
int number_to_stack(int number, stack *s);

/**
 * @brief Funkce vloží do zásobníku značku NIL (prázdný seznam)
 * 
 * @param s zásobník do kterého se bude hodnota vkládat
 * @return int informace o úspěšnosti vkládání
 */
int insert_NIL(stack *s);

/**
 * @brief Funkce vloží do zásobníku značku T (kladná pravdivostní hodnota)
 * 
 * @param s zásobník do kterého se bude hodnota vkládat
 * @return int informace o úspěšnosti vkládání
 */
int insert_T(stack *s);

/**
 * @brief Funkce vloží řetězec do zásobníku
 * 
 * @param s zásobník, do kterého se řetězec bude vkládat
 * @param str řetězec, který se bude do zásobníku vkládat
 * @return int informace o úspěšnosti vkládání
 */
int str_to_stack(stack *s, char str[]);

/**
 * @brief Funkce vloží řetězec do zásobníku obráceně
 * 
 * @param s zásobník, do kterého se řetězec bude vkládat
 * @param str řetězec, který se bude do zásobníku vkládat
 * @return int informace o úspěšnosti vkládání
 */
int str_to_stack_mirror(stack *s, char str[]);

/**
 * @brief Funkce projde vložený řetězec a připraví jej pro
 * funkci QUOTE.
 * 
 * @param str vložený řetězec pro úpravu
 * @return int informace o úspěšnosti
 */
int operator_quote_prepare(char *str);

/**
 * @brief Funkce převede znak malého písmene na znak velkého písmene
 * 
 * @param znak znak, který chcemem převést na velké písmeno
 * @return char znak velkého písmene odpovídající k zadanému písmenu
 */
char to_upper(char znak);

/**
 * @brief Funkce zjistí zda je zadaný znak číslice
 * 
 * @param znak testovaný znak
 * @return int (1 = číslice; 0 = jiný znak)
 */
int is_digit(char znak);

/**
 * @brief Funkce zjistí,  zda všechny znaky řetězce jsou číslicemi a je
 * tedy možné je převést na číslo
 * 
 * @param str testovaný řetězec
 * @return int pravdivostní hodnota (1 = je číslo; 0 = není číslo)
 */
int is_number(char *str);

/**
 * @brief Funkce na základě vstupního parametru rozdělí řetězec na samostatné výrazy
 * 
 * @param src rozdělovaný řetězec
 * @param from od kterého indexu se bude řetězec dělit
 * @return int index znaku, kde končí oddělný výraz (v případě konce řetězce nebo neúspěchu vrací -1)
 */
int split_line(char *src, int from);
