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

#include "loader.h"

/**
 * @brief Metoda našte další část řetězce pro vyhodnocení. Například pro načtení operátoru
 * nebo operandu.
 * 
 * @param s pointer na zásobník
 * @param result výsledná část řetěce
 * @return int informace o úspěšnosti
 */
int next_operator(stack *s, char **result) {
    char znak = '\0';
    char *str;
    char *str2;
    int str_len, list = 0;
    /**
     * @brief Kontrola vstupních parametrů
     * 
     */

    if (!stack_item_count(s)) {
        return 0;
    }

    if (!s || !result) {
        return 0;
    }

    /**
     * @brief Pripojovani jednotlivych znaku do retezce
     * 
     */

    str = malloc(sizeof(char));

    if (!str) {
        return 0;
    }
    
    str[0] = '\0';
    while (stack_item_count(s)) {
        /**
         * @brief Vyskakovací podmínky
         * pro určení konce konce chtěného řetězce
         * 
         */
        
        if (!stack_pop(s, &znak)) {
            break;
        }
        
        if (znak == ' ' && list == 0) {
            break;
        }
    
        if (znak == LIST_BRACKET_OPEN) {
            list++;
        }

        if (znak == LIST_BRACKET_CLOSE) {
            list--;
        }

        str_len = strlen(str);
        str2 = malloc(str_len + 1 + 1);
        strcpy(str2, str);
        str2[str_len] = znak;
        str2[str_len+1] = '\0';

        free(str);
        str = str2;

    }

    *result = str;

    return 1;
}

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
int nextValue(char *input, char *output, int *index, int to) {
    int input_len;

    if (!input || !output || !index) {
        return 0;
    }

    input_len = strlen(input);
    if (!input_len) {
        return 0;
    }

    if (*index >= to) {
        return 0;
    }

    if (input_len <= *index) {
        return 0;
    }

    *output = input[*index];
    *index = *index + 1;

    return 1;

}

/**
 * @brief Funkce převádí řetězec na celé číslo 
 * 
 * @param number pointer na místo, kam se uloží výsledné celé číslo
 * @param str řetězec ve kterém se se má vyskytovat 
 */
void str_to_number(int *number, char str[]) {
    *number = strtol(str, NULL, 10);
}

/**
 * @brief Funkce převádí číslo na řetězec
 * 
 * @param number převáděné číslo, které se má převést na řetězec
 * @param mezivýsledek výsledný řetězec obsahující zadané číslo
 * @return int 
 */
int number_to_str(int number, char str[]) {
    int i, rem, len = 0, n, num;
 
    n = number;
    num = number;

    if(n == 0) {
        str[0] = '0';
        str[1] = '\0';
        return 1;
    }

    if (n < 0) {
        len++;
        n *= -1;
        num *= -1;
    }

    while (n != 0)
    {
        len++;
        n /= 10;
    }
    for (i = 0; i < len; i++)
    {
        rem = num % 10;
        num = num / 10;
        str[len - (i + 1)] = rem + '0';
    }

    if (number < 0) {
        str[0] = '-';
    }

    str[len] = '\0';

    for (i = 0; i < 10; i++) {
    }
    return 1;
}

/**
 * @brief Funkce vlozí veškeré znaky čísla (převedeného na řetězec) do zásobníku
 * 
 * @param number vkládané číslo
 * @param s zásobník, do kterého se číslo vloží
 * @return int informace o úspěšnosti vkládání
 */
int number_to_stack(int number, stack *s) {
    int j;
    char str_number[11];
    char znak;
    int str_len;

    if (!number_to_str(number, str_number)) {
        return 0;
    }

    str_len = strlen(str_number);

    for (j = 0; j < str_len; j++) {
        znak = str_number[j];  
        stack_push(s, &znak);
    }

    return 1;
}

/**
 * @brief Funkce vloží do zásobníku značku NIL (prázdný seznam)
 * 
 * @param s zásobník do kterého se bude hodnota vkládat
 * @return int informace o úspěšnosti vkládání
 */
int insert_NIL(stack *s) {
    char str[] = "NIL";
    int i;
    
    for (i = 0; str[i] != '\0'; i++) {
        if (!stack_push(s, &(str[i]))) {
            return 0;
        }
    }

    return 1;
}

/**
 * @brief Funkce vloží do zásobníku značku T (kladná pravdivostní hodnota)
 * 
 * @param s zásobník do kterého se bude hodnota vkládat
 * @return int informace o úspěšnosti vkládání
 */
int insert_T(stack *s) {
    char znak = 'T';
    return stack_push(s, &znak);

}

/**
 * @brief Funkce vloží řetězec do zásobníku
 * 
 * @param s zásobník, do kterého se řetězec bude vkládat
 * @param str řetězec, který se bude do zásobníku vkládat
 * @return int informace o úspěšnosti vkládání
 */
int str_to_stack(stack *s, char str[]) {
    int i;

    if (!s || !str) {
        return 0;
    }

    for (i = 0; str[i] != '\0'; i++) {
        if (!stack_push(s, &(str[i]))) {
            return 0;
        }
    }

    return 1;
}

/**
 * @brief Funkce vloží řetězec do zásobníku obráceně
 * 
 * @param s zásobník, do kterého se řetězec bude vkládat
 * @param str řetězec, který se bude do zásobníku vkládat
 * @return int informace o úspěšnosti vkládání
 */
int str_to_stack_mirror(stack *s, char str[]) {
    int i;
    int str_len;

    if (!s || !str) {
        return 0;
    }

    str_len = strlen(str);
    if (!str_len) {
        return 0;
    }

    for (i = str_len-1; i >= 0; i--) {
        if (!stack_push(s, &(str[i]))) {
            return 0;
        }
    }

    return 1;
}

/**
 * @brief Funkce projde vložený řetězec a připraví jej pro
 * funkci QUOTE.
 * 
 * @param str vložený řetězec pro úpravu
 * @return int informace o úspěšnosti
 */
int operator_quote_prepare(char *str) {
    int i, x, opened;
    char needle[] = "quote";

    if (!str) {
        return 0;
    }

    /**
     * @brief Nahrazeni zavorek v QUOTE vyrazech
     * 
     */

    opened = 0;
    x = 0;
    for (i = 0; str[i] != '\0'; i++) {
        if (x == 5) {
            if (str[i] == EXPRESSION_BRACKET_OPEN) {
                str[i] = QUOTE_BRACKET_OPEN;
                opened++;
            }

            if (str[i] == EXPRESSION_BRACKET_CLOSE) {
                str[i] = QUOTE_BRACKET_CLOSE;
                opened--;
            }

            if (opened == 0) {
                x = 0;
            }
        } else {
            if (needle[x] == str[i]) {
                x++;
                if (x == 5) {
                    i++;
                }
            } else {
                x = 0;
            }
        }
    }

    return 1;
}

/**
 * @brief Funkce převede znak malého písmene na znak velkého písmene
 * 
 * @param znak znak, který chcemem převést na velké písmeno
 * @return char znak velkého písmene odpovídající k zadanému písmenu
 */
char to_upper(char znak) {
    if (znak >= 'a' && znak <= 'z') {
        return (znak - 32);
    }

    return znak;
}

/**
 * @brief Funkce zjistí zda je zadaný znak číslice
 * 
 * @param znak testovaný znak
 * @return int (1 = číslice; 0 = jiný znak)
 */
int is_digit(char znak) {
    return (znak >= '0' && znak <= '9');
} 

/**
 * @brief Funkce zjistí,  zda všechny znaky řetězce jsou číslicemi a je
 * tedy možné je převést na číslo
 * 
 * @param str testovaný řetězec
 * @return int pravdivostní hodnota (1 = je číslo; 0 = není číslo)
 */
int is_number(char *str) {
    int i;

    for (i = 0; str[i] != '\0'; i++) {
        if (!is_digit(str[i])) {
            return 0;
        }
    }

    return 1;
}


/**
 * @brief Funkce na základě vstupního parametru rozdělí řetězec na samostatné výrazy
 * 
 * @param src rozdělovaný řetězec
 * @param from od kterého indexu se bude řetězec dělit
 * @return int index znaku, kde končí oddělný výraz (v případě konce řetězce nebo neúspěchu vrací -1)
 */
int split_line(char *str, int from) {
    int opened = 0;
    int str_len;
    int i;

    if (!str) {
        return -1;
    }

    str_len = strlen(str);

    if (!str_len) {
        return -1;
    }

    if (str_len <= from) {
        return -1;
    }

    for (i = from; i < str_len; i++) {

        if (str[i] == ' ') {
            if (opened == 0) {
                return (i+1);
            }
            continue;
        }

        if (str[i] == QUOTE_SHORTCUT) {
            continue;
        }

        if (str[i] == EXPRESSION_BRACKET_OPEN) {
            opened++;
        }

        if (str[i] == EXPRESSION_BRACKET_CLOSE) {
            opened--;
        }
    }

    return str_len;

}