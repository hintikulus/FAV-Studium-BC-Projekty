/**
 * @file lisp.c
 * @author Jan Hinterholzinger
 * @brief Hlavní soubor funkcí pro vyhodnocení výrazu a pro spuštění programu
 * @version 0.1
 * @date 2021-01-15
 * 
 * @copyright Copyright (c) 2021
 * 
 */

#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <stddef.h>

#include "operators.h"
#include "loader.h"
#include "list.h"

/* Informace o velikosti vstupu a zásobníkové struktury */
#define MAX_INPUT_LEN 128
#define STACK_SIZE 128

/* Příkaz sloužící k ukončení aplikace */
#define COMMAND_QUIT "(quit)"

/**
 * @brief Funkce pro vyhodnocení výrazů na řádce. Funkce řádek rozdělí
 * na jednotlivé výrazy a ty postupně vyhodnotí.
 * 
 * @param input řádek vstupu s výrazy
 * @param vars pointer na strukturu spojového seznamu s proměnnými
 * @param count pointer na pořadové číslo výrazu
 * @return int informace o úspěšnosti operace
 */
int evaluate(char *input, list *vars, int *count) {
    stack *s = NULL;
    stack *temp = NULL;
    int i = 0, input_len;
    char *operator = NULL;
    char znak = '\0', a;
    fcalc handler;
    int b = 0;
    int opened;
    int quote;
    int x = 0, j = 0;
    input_len = strlen(input);

    if(!input) {
        return EXIT_FAILURE;
    }

    if (!input_len) {
        return EXIT_FAILURE;
    }

    if (!vars || !count) {
        return EXIT_FAILURE;
    }

    /* Cyklus pro obsluhu různých výrazů na řádce */
    while ((x = split_line(input, x)) != -1) {
        (*count)++;
        printf("[%d]> ", *count);


        if(input[x-1] == ' ') {
            b = x-1;
        } else {
            b = x;
        }

        for(j = i; j < b; j++) {
            printf("%c", input[j]);
        }

        printf("\n");

        s = stack_create(input_len, sizeof(char));
        if (!s) {
            return EXIT_FAILURE;
        }

        if (input[i] == EXPRESSION_BRACKET_OPEN || input[i] == QUOTE_SHORTCUT) { /* Případ, kdy se jedná o běžný výraz */
            operator_quote_prepare(input);

            opened = 0;
            quote = 0;

            /* Procházení všech znaků výrazů a vkládání do zásobníku a případné vyhodnocování*/
            while (nextValue(input, &znak, &i, x)) {
                if (quote == 1) {
                    if (opened) {
                        if (znak == EXPRESSION_BRACKET_OPEN) {
                            opened++;
                            znak = QUOTE_BRACKET_OPEN;
                            if (opened == 0) quote = 0;
                        }

                        if (znak == EXPRESSION_BRACKET_CLOSE) {
                            opened--;
                            znak = QUOTE_BRACKET_CLOSE;
                            if (opened == 0) quote = 0;
                        }
                    }
                }

                if (znak == QUOTE_SHORTCUT) {
                    quote = 1;

                    if (nextValue(input, &znak, &i, x) && znak == EXPRESSION_BRACKET_OPEN) {                    
                        opened++;
                        znak = QUOTE_BRACKET_OPEN;
                    }
                }

                if (znak != EXPRESSION_BRACKET_CLOSE) {
                    /* Dokud výraz není uzavřen, tak se všechny znaky vkládají do zásobníku */
                    znak = to_upper(znak);
                    if(znak != '\n' || znak != '\000') {
                        stack_push(s, &znak);
                    }
                } else {
                    /* Jakmile se výraz uzavírá, tak se spouští vyhodnocování výrazu */

                    temp = stack_create(input_len, sizeof(char));
                    if (!temp) {
                        stack_free(&s);
                        return EXIT_FAILURE;
                    }

                    /* Přesun znaků do pomocného zásobníku */
                    while (stack_item_count(s) && stack_peek(s, &a) && a != EXPRESSION_BRACKET_OPEN) {

                        if (!stack_pop(s, &a)) {
                            stack_free(&s);
                            stack_free(&temp);
                            return EXIT_FAILURE;
                        }

                        if (!stack_push(temp, &a)) {
                            stack_free(&s);
                            stack_free(&temp);
                            return EXIT_FAILURE;
                        }
                        
                    }

                    /* Pokus o získání operátoru */
                    if (!next_operator(temp, &operator)) {
                        if (stack_peek(s, &znak) && znak == EXPRESSION_BRACKET_OPEN) {
                            if (stack_pop(s, &znak)) {
                                printf("NIL\n");
                                stack_free(&temp);
                                break;
                            }
                        }

                    /* Operátor se podařilo načíst */
                    } else {
                        if ((handler = get_handler(operator))) { /* Hledání odpovídající funkce pro operátor */
                            stack_pop(s, &a);

                            /* Vlastní operace dle operátoru */
                            if (handler(temp, s, vars)) {
                                free(operator);
                                stack_free(&temp);
                                stack_free(&s);
                                return EXIT_FAILURE;
                            }

                            free(operator);

                        } else {
                            printf("CHYBA: Prvni prvek seznamu neni operator nebo funkce\n");
                            stack_free(&temp);
                            stack_free(&s);
                            return EXIT_FAILURE;
                        }

                        stack_free(&temp);
                    }
                }

            }
        } else {
            /* Případ, kdy výraz začíná konstantou nebo proměnnou */
            while (nextValue(input, &znak, &i, x)) {
                znak = to_upper(znak);

                if (znak == ' ') {
                    break;
                }

                if (!stack_push(s, &znak)) {
                    break;
                }

            } 

            if (!next_operator(s, &operator)) {
                stack_free(&s);
                continue;
            }

            /* Testování, zda se jedná o proměnnou, konstantu nebo neplatný výraz */

            znak = ' '; /* Proměnná právě využita jako označovač */
            if(strlen(operator) > 1 && operator[0] == 10) {
                operator = &(operator[1]);
                znak = '1';
            }
            if (list_get(vars, operator, &b)) {
                printf("%d\n", b);
            } else if (is_number(operator)) {
                str_to_stack_mirror(s, operator);
            } else {
                printf("Neplatny vyraz\n");
            }

            if(znak == ' ') {
                free(operator);
            } else if (znak == '1'){
                free(operator - sizeof(char));
            }
        }
        
        /* Výpis výsledku, pokud nebylo již provedeno  */

        if (stack_item_count(s)) {
    
            temp = stack_create(input_len, sizeof(char));

            if (!temp) {
                stack_free(&s);
                return EXIT_FAILURE;
            }

            /* Přesun prvků do pomocného zásobníku, aby prvky byly ve správném pořadí */
            while (stack_item_count(s) && stack_pop(s, &a) && stack_push(temp, &a)) {
            }

            /* Výpis jednotlivých znaků výsledku */
            while (stack_item_count(temp)) {
                if (!stack_pop(temp, &a)) {
                    break;
                }
                
                /* Přepsání pomocných závorek zpět */
                if (a == QUOTE_BRACKET_OPEN) a = EXPRESSION_BRACKET_OPEN;
                if (a == QUOTE_BRACKET_CLOSE) a = EXPRESSION_BRACKET_CLOSE;

                if (a == LIST_BRACKET_OPEN) a = EXPRESSION_BRACKET_OPEN;
                if (a == LIST_BRACKET_CLOSE) a = EXPRESSION_BRACKET_CLOSE;
                
                if (a == '\n') {
                    continue;
                }

                if(!(stack_item_count(temp) == 0 && a == ' ')) {
                    printf("%c", a);
                }
            }

            printf("\n");
            stack_free(&temp);
        
        }

        stack_free(&s);

        /* Posun ukazatele na další podřetězec dalšího výrazu */
        i = x;
    } 
    
    return EXIT_SUCCESS;
}

/**
 * @brief Hlavní funkce - vstupní bod programu
 * Funkce načítá výrazy z případně zadaného souboru.
 * Nebo výrazy prijímá od vstupu od uživatele.
 * 
 * @param argc počet spouštěcích argumentů
 * @param argv pole spuštěcích parametrů
 * @return int informace o ukončení programu
 */
int main(int argc, char** argv) {
    char input[MAX_INPUT_LEN] = { 0 };
    list *vars = NULL;
    FILE *f = NULL;
    int counter = 0;
    char buff[255];
    int test;

    if (argc > 1) {
        f = fopen(argv[1], "r");
    }
    
    vars = list_create(); /* Vytvoření spojového seznamu pro proměnné */
    if (!vars) {
        return EXIT_FAILURE;
    }

    while (f && fgets(buff, 255, f)) {
            for(test = 0; test < 255; test++) {
                if(buff[test] == '\n') {
                    buff[test] = '\000';
                }
            }
            evaluate(buff, vars, &counter);
    
    }

    if (f) {
        fclose(f);
        list_free(&vars);
        return EXIT_SUCCESS;
    }
    
    /* Konzole pro vstup výrazů od uživatele */
    while (1) {
        fgets(input, MAX_INPUT_LEN, stdin);
        input[strlen(input) - 1] = '\000'; /* Odstranění enteru ze vstupu */

        if (!strcmp(input, COMMAND_QUIT)) {
            counter++;
            printf("[%d]> %s\nBye.\n", counter, input);
            break;
        }
        
        evaluate(input, vars, &counter); /* Vyhodnocení výrazu */
    

    }
    
    list_free(&vars);
    return EXIT_SUCCESS;
}
