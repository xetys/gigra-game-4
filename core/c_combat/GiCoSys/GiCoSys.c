#ifdef HAVE_CONFIG_H
#include "config.h"
#endif
#include "php.h"
 
#define PHP_GICOSYS_VERSION "1.0"
#define PHP_GICOSYS_EXTNAME "GiCoSys"
 
extern zend_module_entry gicosys_module_entry;
#define phpext_my_extension_ptr &gicosys_module_entry
 
// declaration of a custom my_function()
PHP_FUNCTION(GiCoSys_ComputeBattle);
 
// list of custom PHP functions provided by this extension
// set {NULL, NULL, NULL} as the last record to mark the end of list
static zend_function_entry my_functions[] = {
    PHP_FE(GiCoSys_ComputeBattle, NULL)
    {NULL, NULL, NULL}
};
 
// the following code creates an entry for the module and registers it with Zend.
zend_module_entry gicosys_module_entry = {
#if ZEND_MODULE_API_NO >= 20010901
    STANDARD_MODULE_HEADER,
#endif
    PHP_GICOSYS_EXTNAME,
    my_functions,
    NULL, // name of the MINIT function or NULL if not applicable
    NULL, // name of the MSHUTDOWN function or NULL if not applicable
    NULL, // name of the RINIT function or NULL if not applicable
    NULL, // name of the RSHUTDOWN function or NULL if not applicable
    NULL, // name of the MINFO function or NULL if not applicable
#if ZEND_MODULE_API_NO >= 20010901
    PHP_GICOSYS_VERSION,
#endif
    STANDARD_MODULE_PROPERTIES
};
 
ZEND_GET_MODULE(gicosys)
 


#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <math.h>
#include "battle.h"


char ResultBuffer[64*1024];    // Буфер выходных данных.(Buffer fuer ausgehende Daten)

// Настройки выпадения лома.(TF Konfiguration)
int DefenseInDebris = 0, FleetInDebris = 0;
int Rapidfire = 1;  // 1: RapidFire an

// Таблица стоимости.(Preisliste)
typedef struct UnitPrice { long m, k, d; } UnitPrice;
static UnitPrice FleetPrice[] = {
 { 2000, 2000, 0 }, 
 { 6000, 6000, 0 }, 
 { 3000, 1000, 0 }, 
 { 6000, 4000, 0 },
 { 20000, 7000, 2000 }, 
 { 45000, 15000, 0 }, 
 { 10000, 20000, 10000 }, 
 { 10000, 6000, 2000 },
 { 0, 1000, 0 }, 
 { 50000, 25000, 15000 }, 
 { 0, 2000, 500 }, 
 { 60000, 50000, 15000 },
 { 5000000, 4000000, 1000000 }, 
 { 30000, 40000, 15000 },
 { 30000, 40000, 15000 },
 { 30000, 40000, 15000 },
 { 30000, 40000, 15000 },
 { 30000, 40000, 15000 }
};
static UnitPrice DefensePrice[] = {
 { 2000, 0, 0 }, { 1500, 500, 0 }, { 6000, 2000, 0 }, { 20000, 15000, 2000 },
 { 2000, 6000, 0 }, { 50000, 50000, 30000 }, { 10000, 10000, 0 }, { 50000, 50000, 0 }
};

  
//{ huelle,     schilde,        angriff,    kapa?   ,   emp-wert    }
TechParam fleetParam[18] = { // Flotten Daten
 {  250,        50,             100,        250,        0      },  //Tri-Fighter
 {  170,        30,             1,          10000,      0      },  //Recycler
 {  100,        1,              0,          30,         0      },  //Spionagesonde
 {  1000,       120,            250,        200,        0      },  //Stormfighter
 {  1700,       200,            450,        900,        0      },  //Raider
 {  1500,       100,            500,        4000,       0      },  //Tarnbomber
 {  1500,       1500,           1,          100000,     0      },  //Kolonisationseinheit
 {  35000,      65,             10,         100000,     0      },  //Invasionseinheit
 {  5000,       750,            2600,       2000,       0      },  //C-Force
 {  5500,       1000,           3200,       24000,      0      },  //Imp. Zerstörer
 {  12000,      1000,           4500,       34000,      0      },  //Imp. Sterneneinheit
 {  400,        5,              1,          10000,      0      },  //Kl. Handelsschiff
 {  1000,       50,             5,          50000,      0      },  //Gr. Handelsschiff
 {  1800000,    150000,         200000,     25000000,   0      },  //Lunares Sternenschiff
 {  350,        10,             1,          0,          0      },  //Solarsat
 {  50000,      35000,          50,         5000000,    0      },  //Imp. Transporter
 {  7500,       1500,           3000,       2000,       -300   },  //Emp Bomber
 {  30000,      10000,          30000,     1400,       1000    }   //War-Drainer
};


TechParam defenseParam[8] = { // ТТХ Обороны.
//{ huelle,     schilde,        angriff,    kapa,   emp-wert    }
 {  200,        40,             80,         0,      0       },  //Raketengeschuetz,
 {  400,        100,            160,        0,      0       },  //Leichter Laserturm
 {  500,        150,            350,        0,      0       },  //Schwerer Laserturm
 {  6000,       1000,           3000,       0,      0       },  //Elektronenkanone
 {  4000,       1000,           2000,       0,      -500    },  //EMP-Werfer
 {  4500,       2000,           4000,       0,      0       },  //Plasmaturm
 {  10000,      2500,           5000,      0,      0       },  //Nukleargeschuetz
 {  180000,     320000,         750000,     0,      0       }   //Mikrotonenkanone
};

// Rapidfire werte
static long FleetRapid[][18] = {
//{ trif,   recycler,   spio,   stormf,     raider,     tarnb,  kolo,   inva,   c-force,    impzer,     impstern,   khs,    ghs,    !!luna!!,   solsat,     imptran,    empbom,     ward}
 {  0,      0,          800,    0,          0,          0,      0,      0,      800,        0,          0,          0,      0,      0,          800,        0,          0,          0},     //trifighter
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //recycler
 {  0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0},     //spiosonde
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          800,        0,          0,      0,      0,          800,        933,        0,          0},     //stormfighter
 {  833,    0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //raider
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //tarnbomber
 {  0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0},     //koloschiff
 {  0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0},     //Invasionseinheit
 {  0,      900,        800,    900,        857,        500,    800,    900,    0,          667,        0,          900,    800,    0,          800,        888,        0,          0},     //c-force
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //imp. zerr
 {  0,      0,          800,    0,          0,          0,      0,      0,      667,        0,          0,          0,      0,      0,          800,        0,          0,          0},     //imp. stern
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //k. hs
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //g. hs
 {  999,    999,        999,    998,        995,        995,    999,    999,    980,        971,        960,        999,    996,    0,          999,        800,        971,        800},     //Luna
 {  0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0},     //solsat
 {  0,      0,          800,    0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          800,        0,          0,          0},     //imp. trans
 {  0  ,    500,        800,    0  ,        0  ,        0  ,    500,    500,    500,        500,        0,          500,    500,    0,          800,        500,        0,          950},     //emp bomber
 {  667,    667,        800,    667,        667,        667,    667,    667,    667,        667,        0,          667,    500,    0,          667,        500,        0,          0}      //War-Drainer
};

static long DefenseRapid[][8] = {
//{ rak,    llaser, slaser,     ekanone,    empw,   plasma, nuklear,    mikroton    }
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //trifighter
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //recycler
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //spiosonde
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //stormfighter
 {  933,    0,      0,          0,          0,      0,      0,          0,          },     //raider
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //tarnbomber
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //koloschiff
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //Invasionseinheit
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //c-force
 {  960,    916,    875,        800,        800,    0,      0,          0,          },     //imp. zerr
 {  0,      875,    0,          667,        0,      0,      0,          0,          },     //imp. stern
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //k. hs
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //g. hs
 {  999,    999,    996,        983,        980,    971,    960,        0,          },     //Luna
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //solsat
 {  0,      0,      0,          0,          0,      0,      0,          0,          },     //imp. trans
 {  500,    500,    500,        500,        500,    500,    500,        0,          },     //emp bomber
 {  667,    667,    667,        667,        667,    667,    667,        0,          }      //War-Drainer
};

static long DefenseFleetRapid[8][18] = {
 //{ trif,   recycler,   spio,   stormf,     raider,     tarnb,  kolo,   inva,   c-force,    impzer,     impstern,   khs,    ghs,    !!luna!!,   solsat,     imptran,    empbom,     ward}
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // Rak
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // l laser
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // s laser
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // e kanone
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // emp werfer
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // plasmaturm
 {   0,      0,          0,      0,          0,          0,      0,      0,      0,          0,          0,          0,      0,      0,          0,          0,          0,          0}, // nuklear
 {   998,    998,        998,    996,        990,        990,    550,    550,    960,        933,        916,        998,    992,    500,        998,        0,          933,        500}  // mikro
};
// ==========================================================================================

// Daten aus Datei laden
void * FileLoad(char *filename, unsigned long *size, char * mode)
{
    FILE*   f;
    void*   buffer;
    unsigned long     filesize;

    if(size) *size = 0;

    f = fopen(filename, mode);
    if(f == NULL) return NULL;

    fseek(f, 0, SEEK_END);
    filesize = ftell(f);
    fseek(f, 0, SEEK_SET);

    buffer = malloc(filesize + 10);
    if(buffer == NULL)
    {
        fclose(f);
        return NULL;
    }
    memset ( buffer, 0, filesize+10);

    fread(buffer, filesize, 1, f);
    fclose(f);
    if(size) *size = filesize;    
    return buffer;
}

// und wieder speichern
int FileSave(char *filename, void *data, unsigned long size)
{
    FILE *f = fopen(filename, "wt");
    if(f == NULL) return 0;

    fwrite(data, size, 1, f);
    fclose(f);
    return 1;
}

// ==========================================================================================
// definitionen
// Mersenne Twister.

#define N 624
#define M 397
#define MATRIX_A 0x9908b0dfUL
#define UPPER_MASK 0x80000000UL
#define LOWER_MASK 0x7fffffffUL

static unsigned long mt[N];
static int mti=N+1;

// wir wollen nen schoenes zufalls system
void init_genrand(unsigned long s)
{
    mt[0]= s & 0xffffffffUL;
    for (mti=1; mti<N; mti++) {
        mt[mti] = 
        (1812433253UL * (mt[mti-1] ^ (mt[mti-1] >> 30)) + mti); 
        mt[mti] &= 0xffffffffUL;
    }
}

unsigned long genrand_int32(void)
{
    unsigned long y;
    static unsigned long mag01[2]={0x0UL, MATRIX_A};

    if (mti >= N) {
        int kk;

        if (mti == N+1)
            init_genrand(5489UL);

        for (kk=0;kk<N-M;kk++) {
            y = (mt[kk]&UPPER_MASK)|(mt[kk+1]&LOWER_MASK);
            mt[kk] = mt[kk+M] ^ (y >> 1) ^ mag01[y & 0x1UL];
        }
        for (;kk<N-1;kk++) {
            y = (mt[kk]&UPPER_MASK)|(mt[kk+1]&LOWER_MASK);
            mt[kk] = mt[kk+(M-N)] ^ (y >> 1) ^ mag01[y & 0x1UL];
        }
        y = (mt[N-1]&UPPER_MASK)|(mt[0]&LOWER_MASK);
        mt[N-1] = mt[M-1] ^ (y >> 1) ^ mag01[y & 0x1UL];

        mti = 0;
    }
  
    y = mt[mti++];

    y ^= (y >> 11);
    y ^= (y << 7) & 0x9d2c5680UL;
    y ^= (y << 15) & 0xefc60000UL;
    y ^= (y >> 18);

    return y;
}

double genrand_real1(void) { return genrand_int32()*(1.0/4294967295.0); }
double genrand_real2(void) { return genrand_int32()*(1.0/4294967296.0); }

void MySrand (unsigned long seed)
{
    init_genrand (seed);
    //srand (seed);
}

// gib mir ne zahl zwischen a und b, inkl. a und b
unsigned long MyRand (unsigned long a, unsigned long b)
{
    return a + (unsigned long)(genrand_real1 () * (b - a + 1));
    //return a + (unsigned long)((rand ()*(1.0/RAND_MAX)) * (b - a + 1));
}

// ==========================================================================================

static char *longnumber (u64 n)
{
    static char retbuf [32];
    char *p = &retbuf [sizeof (retbuf) - 1];
    int i = 0;

    if (n == 0) return "0";
    *p = '\0';
    for (i = 0; n; i++)
    {
        *--p = '0' + n % 10;
        n /= 10;
    }
    return p;
}

// ���������� ��������� ��������� ����.
void SetDebrisOptions (int did, int fid)
{
    if (did < 0) did = 0;
    if (fid < 0) fid = 0;
    if (did > 100) did = 100;
    if (fid > 100) fid = 100;
    DefenseInDebris = did;
    FleetInDebris = fid;
}

void SetRapidfire (int enable) { Rapidfire = enable & 1; }

float withBonus(Slot *s, long value)
{
    return (s->bonus < 100 ? 100 : s->bonus) * 0.01 * value;
}

// �������� ������ ��� ������ � ���������� ��������� ��������.
Unit *InitBattleAttackers (Slot *a, int anum, int objs)
{
    Unit *u;
    int aid = 0;
    int i, n, ucnt = 0, obj;
    u = (Unit *)malloc (objs * sizeof(Unit));
    if (u == NULL) return u;
    memset (u, 0, objs * sizeof(Unit));
    


    
    for (i=0; i<anum; i++, aid++) {
        for (n=0; n<18; n++)
        {
            for (obj=0; obj<a[i].fleet[n]; obj++) {
                u[ucnt].hull = u[ucnt].hullmax = withBonus(&a[i],fleetParam[n].structure * 0.1 * (10+a[i].armor));
                u[ucnt].angbonus = 0;
                u[ucnt].obj_type = 100 + n;
                u[ucnt].slot_id = aid;
                ucnt++;
            }
        }
    }

    return u;
}

/**
 * Lädt die Verteidiger Techs
 * 
 * 
 * 
 **/
Unit *InitBattleDefenders (Slot *d, int dnum, int objs)
{
    Unit *u;
    int did = 0;
    int i, n, ucnt = 0, obj;
    u = (Unit *)malloc (objs * sizeof(Unit));
    if (u == NULL) return u;
    memset (u, 0, objs * sizeof(Unit));
    

    

    for (i=0; i<dnum; i++, did++) {
        for (n=0; n<18; n++)
        {
            for (obj=0; obj<d[i].fleet[n]; obj++) {
                u[ucnt].hull = u[ucnt].hullmax = withBonus(&d[i],(fleetParam[n].structure * 0.1 * (10+d[i].armor)));
                u[ucnt].angbonus = 0;
                u[ucnt].obj_type = 100 + n;
                u[ucnt].slot_id = did;
                ucnt++;
            }
        }
        for (n=0; n<8; n++)
        {
            for (obj=0; obj<d[i].def[n]; obj++) {
                u[ucnt].hull = u[ucnt].hullmax = defenseParam[n].structure * 0.1 * (10+d[i].armor) / 10;
                u[ucnt].angbonus = 0;
                u[ucnt].obj_type = 200 + n;
                u[ucnt].slot_id = did;
                ucnt++;
            }
        }
    }

    return u;
}
long MyMax(long a, long b)
{
    return a > b ? a : b;   
}
// ������� a => b. ���������� ����. aweap - ������� ��������� ���������� ��� ����� "a".
// absorbed - ���������� ������������ ������ ����� (��� ����, ���� �������, �� ���� ��� ����� "b").
// loss - ���������� ������ (��������� ����� ������+��������).
//long UnitShoot (Unit *a, int aweap, Unit *b, u64 *absorbed, u64 *dm, u64 *dk )
long UnitShoot (Unit *a, Slot * slot, Unit *b, u64 *absorbed, u64 *dm, u64 *dk )
{
    float prc, depleted;
    long apower, adelta = 0, emp;
    int aweap = slot->weap;
    
    if (a->obj_type < 200) emp = fleetParam[a->obj_type-100].emp;
    else emp = defenseParam[a->obj_type-200].emp;
    
    
    if (a->obj_type < 200) apower = MyMax(0,(fleetParam[a->obj_type-100].attack * (10+aweap) / 10) + a->angbonus);
    else apower = MyMax(0,(defenseParam[a->obj_type-200].attack * (10+aweap) / 10) + a->angbonus);
    //printf("a:angbonus %i bei %i\n",a->angbonus,a->obj_type);
    
    apower = withBonus(slot,apower);
    
    
    
    if (b->exploded) return apower; // ��� �������.
    //noch nich kaputt, check emp
    if(emp != 0)
    {
        if(emp > 0)
        {
            //Das ist die Draining geschichte, wir ziehen ab und fügen es uns hinzu   
            b->angbonus -= emp;
            a->angbonus += emp;
        }
        else
            b->angbonus += emp;
    }
    //printf("b:angbonus %i bei %i\n",b->angbonus,b->obj_type);
    
    if (b->shield == 0) {  // ����� ���.
        if (apower >= b->hull) b->hull = 0;
        else b->hull -= apower;
        
        
    }
    else { // �������� �� �����, � ���� ������� �����, �� � �� �����.
        prc = (float)b->shieldmax * 0.01;
        depleted = floor ((float)apower / prc);
        if (b->shield < (depleted * prc)) {
            *absorbed += (u64)b->shield;
            adelta = apower - b->shield;
            if (adelta >= b->hull) b->hull = 0;
            else b->hull -= adelta;
            b->shield = 0;
        }
        else {
            b->shield -= depleted * prc;
            *absorbed += (u64)apower;
        }
    }
    if (b->hull <= b->hullmax * 0.7 && b->shield == 0) {    // �������� � �������� ����.
        if (MyRand (0, 99) >= ((b->hull * 100) / b->hullmax) || b->hull == 0) {
            if (b->obj_type >= 200) {
                *dm += (u64)(ceil(DefensePrice[b->obj_type-200].m * ((float)DefenseInDebris/100.0f)));
                *dk += (u64)(ceil(DefensePrice[b->obj_type-200].k * ((float)DefenseInDebris/100.0f)));
            }
            else {
                *dm += (u64)(ceil(FleetPrice[b->obj_type-100].m * ((float)FleetInDebris/100.0f)));
                *dk += (u64)(ceil(FleetPrice[b->obj_type-100].k * ((float)FleetInDebris/100.0f)));
            }
            b->exploded = 1;
        }
    }
    return apower;
}

// ��������� ���������� ������� � �������. ���������� ���������� ���������� ������.
int WipeExploded (Unit **slot, int amount)
{
    Unit *src = *slot, *tmp;
    int i, p = 0, exploded = 0;
    tmp = (Unit *)malloc (sizeof(Unit) * amount);
    for (i=0; i<amount; i++) {
        if (!src[i].exploded) tmp[p++] = src[i];
        else exploded++;
    }
    free (src);
    *slot = tmp;
    return exploded;
}

// ��������� ��� �� ������� �����. ���� �� � ������ ����� ����� �� ����������, �� ��� ������������� ������ ��������.
int CheckFastDraw (Unit *aunits, int aobjs, Unit *dunits, int dobjs)
{
    int i;
    for (i=0; i<aobjs; i++) {
        if (aunits[i].hull != aunits[i].hullmax) return 0;
    }
    for (i=0; i<dobjs; i++) {
        if (dunits[i].hull != dunits[i].hullmax) return 0;
    }
    return 1;
}

// ������������� HTML-��� �����.
// ���� techs = 1, �� �������� ���������� (� ������� ���������� ���������� �� ����).
static char * GenSlot (char * ptr, Unit *units, int slot, int objnum, Slot *a, Slot *d, int attacker)
{
    Slot *s = attacker ? a : d;
    Slot coll;
    Unit *u;
    int n, i, count = 0;
    unsigned long sum = 0;

    // ������� ��� ����� � ����.
    memset (&coll, 0, sizeof(Slot));
    for (i=0; i<objnum; i++) {
        u = &units[i];
        if (u->slot_id == slot) {
            if (u->obj_type < 200) { coll.fleet[u->obj_type-100]++; sum++; }
            else { coll.def[u->obj_type-200]++; sum++; }
        }
    }

    if ( slot > 0) ptr += sprintf ( ptr, ",{", slot );
    else ptr += sprintf ( ptr, "{", slot );

    ptr += sprintf (ptr, "\"id\":%i,", s[slot].id );

    for (n=0; n<18; n++) {      // �����
        if(n==17 && attacker)
            ptr += sprintf ( ptr, "\"%i\":%i", 101+n, coll.fleet[n]);
        else
            ptr += sprintf ( ptr, "\"%i\":%i,", 101+n, coll.fleet[n]);
        
    }

    if ( !attacker)             // �������
    {
        for (n=0; n<8; n++) {
            if(n<7)
                ptr += sprintf ( ptr, "\"%i\":%i,", 201+n, coll.def[n]);
            else
                ptr += sprintf ( ptr, "\"%i\":%i", 201+n, coll.def[n]);
        }
    }

    ptr += sprintf ( ptr, "}" );
    return ptr;
}
long HitQuote(Slot *s)
{
    int hq = (s->bonus < 100 ? 100 : s->bonus) - 100 + s->hitquote;
    
    return hq > 100 ? 100 : hq;
}
int DoBattle (Slot *a, int anum, Slot *d, int dnum)
{
    long slot, i, n, aobjs = 0, dobjs = 0, idx, rounds, sum = 0;
    long apower, rapidfire, rapidchance, fastdraw,thisroundquote;
    Unit *aunits, *dunits, *unit;
    char * ptr = ResultBuffer, * res, *round_patch;

    u64         shoots[2], spower[2], absorbed[2],shit[2]; // ����� ���������� �� ���������.    
    u64         dm = 0, dk = 0;             // ���� ��������

    // ��������� ����������� ������ �� ���.
    for (i=0; i<anum; i++) {
        for (n=0; n<18; n++) aobjs += a[i].fleet[n];
    }
    for (i=0; i<dnum; i++) {
        for (n=0; n<18; n++) dobjs += d[i].fleet[n];
        if (i == 0) {
            for (n=0; n<8; n++) dobjs += d[i].def[n];
        }
    }

    // ����������� ������ ������ ������.
    aunits = InitBattleAttackers (a, anum, aobjs);
    if (aunits == NULL) {
        return 0;
    }
    dunits = InitBattleDefenders (d, dnum, dobjs);
    if (dunits == NULL) {
        return 0;
    }

    ptr += sprintf (ptr, "{");
    //round_patch = ptr + 15;
    ptr += sprintf (ptr, "\"rounds\":[");

    for (rounds=0; rounds<6; rounds++)
    {
        if (aobjs == 0 || dobjs == 0) break;

        // �������� ����������.
        shoots[0] = shoots[1] = 0;
        shit[0] = shit[1] = 0;
        spower[0] = spower[1] = 0;
        absorbed[0] = absorbed[1] = 0;

        // �������� ����.
        for (i=0; i<aobjs; i++) {
            if (aunits[i].exploded) aunits[i].shield = aunits[i].shieldmax = 0;
            else aunits[i].shield = aunits[i].shieldmax = withBonus(a,fleetParam[aunits[i].obj_type-100].shield * (10+a[aunits[i].slot_id].shld) / 10);
        }
        for (i=0; i<dobjs; i++) {
            if (dunits[i].exploded) dunits[i].shield = dunits[i].shieldmax = 0;
            else {
                if (dunits[i].obj_type >= 200) dunits[i].shield = dunits[i].shieldmax = withBonus(d,defenseParam[dunits[i].obj_type-200].shield * (10+d[dunits[i].slot_id].shld) / 10);
                else dunits[i].shield = dunits[i].shieldmax = withBonus(d,fleetParam[dunits[i].obj_type-100].shield * (10+d[dunits[i].slot_id].shld) / 10);
            }
        }

        // ���������� ��������.
        for (slot=0; slot<anum; slot++)     // ���������
        {
            for (i=0; i<aobjs; i++) {
                thisroundquote = MyRand(HitQuote(a),100);
                rapidfire = 1;
                unit = &aunits[i];
                if (unit->slot_id == slot) {
                    // �������.
                    while (rapidfire) {
                        
                        idx = MyRand (0, dobjs-1);
                        apower = UnitShoot (unit, &a[slot], &dunits[idx], &absorbed[1], &dm, &dk );
                        
                        shoots[0]++;
                        
                        if(!(MyRand(0,99) < thisroundquote))
                        {
                            rapidfire = 0;
                            continue;
                        }
                        shit[0]++;
                        
                        spower[0] += apower;
                        if (unit->obj_type < 200) { // ������ ���� �������� ��������� ���������.
                            if (dunits[idx].obj_type < 200) rapidchance = FleetRapid[unit->obj_type-100][dunits[idx].obj_type-100];
                            else rapidchance = DefenseRapid[unit->obj_type-100][dunits[idx].obj_type-200];
                            rapidfire = MyRand (0, 999) < rapidchance;
                        }
                        else rapidfire = 0;
                        if (Rapidfire == 0) rapidfire = 0;
                    }
                }
            }
        }
        for (slot=0; slot<dnum; slot++)     // �������������
        {
            for (i=0; i<dobjs; i++) {
                rapidfire = 1;
                thisroundquote = MyRand(HitQuote(d),100);
                unit = &dunits[i];
                if (unit->slot_id == slot) {
                    // �������.
                    while (rapidfire) {
                        idx = MyRand (0, aobjs-1);
                        apower = UnitShoot (unit, &d[slot], &aunits[idx], &absorbed[0], &dm, &dk );
                        
                        shoots[1]++;
                        
                        if(!(MyRand(0,99) < thisroundquote))
                        {
                            rapidfire = 0;
                            continue;
                        }
                        shit[1]++;
                        
                        spower[1] += apower;
                        if (unit->obj_type < 200) { // ������ ���� �������� ��������� ���������.
                            if (aunits[idx].obj_type < 200) rapidchance = FleetRapid[unit->obj_type-100][aunits[idx].obj_type-100];
                            else rapidchance = DefenseRapid[unit->obj_type-100][aunits[idx].obj_type-200];
                            rapidfire = MyRand (0, 999) < rapidchance;
                        }
                        else if(unit->obj_type > 200)
                        {
                            rapidchance = DefenseFleetRapid[unit->obj_type-200][aunits[idx].obj_type-100];
                            rapidfire = MyRand (0, 999) < rapidchance;
                        }
                        else rapidfire = 0;
                        if (Rapidfire == 0) rapidfire = 0;
                    }
                }
            }
        }

        // ������� �����?
        fastdraw = CheckFastDraw (aunits, aobjs, dunits, dobjs);

        // ��������� ���������� ������� � �������.
        aobjs -= WipeExploded (&aunits, aobjs);
        dobjs -= WipeExploded (&dunits, dobjs);

        // Round.
        //ptr += sprintf ( ptr, "i:%i;a:8:", rounds );
        if(rounds > 0)
            ptr += sprintf ( ptr, ",{\"ashoot\":%s,", longnumber(shoots[0]) );
        else
            ptr += sprintf ( ptr, "{\"ashoot\":%s,", longnumber(shoots[0]) );
        ptr += sprintf ( ptr, "\"ashit\":%s,", longnumber(shit[0]) );
        ptr += sprintf ( ptr, "\"apower\":%s,", longnumber(spower[0]) ); 
        ptr += sprintf ( ptr, "\"dabsorb\":%s,", longnumber(absorbed[1]) );
        ptr += sprintf ( ptr, "\"dshoot\":%s,", longnumber(shoots[1]) );
        ptr += sprintf ( ptr, "\"dshit\":%s,", longnumber(shit[1]) );
        ptr += sprintf ( ptr, "\"dpower\":%s,", longnumber(spower[1]) );
        ptr += sprintf ( ptr, "\"aabsorb\":%s,", longnumber(absorbed[0]) );
        ptr += sprintf ( ptr, "\"attackers\":[" );
        for (slot=0; slot<anum; slot++) {
            ptr = GenSlot (ptr, aunits, slot, aobjs, a, d, 1);
        }
        ptr += sprintf ( ptr, "]," );
        ptr += sprintf ( ptr, "\"defenders\":[", dnum );
        for (slot=0; slot<dnum; slot++) {
            ptr = GenSlot (ptr, dunits, slot, dobjs, a, d, 0);
        }
        ptr += sprintf ( ptr, "]" );
        //if(fastdraw ||)
        ptr += sprintf ( ptr, "}" );

        if (fastdraw) break;
    }

    //*round_patch = '0' + (rounds);
    
    // ���������� ���.
    if (aobjs > 0 && dobjs == 0){ // ��������� �������
        res = "awon";
    }
    else if (dobjs > 0 && aobjs == 0) { // ��������� ��������
        res = "dwon";
    }
    else    // �����
    {
        res = "draw";
    }

    ptr += sprintf (ptr, "],\"result\":\"%s\",", res);
    ptr += sprintf (ptr, "\"dm\":%s,", longnumber (dm));
    ptr += sprintf (ptr, "\"dk\":%s}", longnumber (dk));
    
    free (aunits);
    free (dunits);
    return 1;
}

// ==========================================================================================
// ������������� ������� ������ - �������� ������ �� �� � ������������ �� �� ��������.

typedef struct SimParam {
    char    name[32];
    char    string[64];
    unsigned long value;
} SimParam;
static SimParam *simargv;
static long simargc = 0;

// ������������� ������ ���� %EF%F0%E8%E2%E5%F2 � �������� ������.
static void hexize (char *string)
{
    int hexnum;
    char *temp, c, *oldstring = string;
    long length = (long)strlen (string), p = 0, digit = 0;
    temp = (char *)malloc (length + 1);
    if (temp == NULL) return;
    while (length--) {
        c = *string++;
        if (c == 0) break;
        if (c == '%') { 
            digit = 1;
        }
        else {
            if (digit == 1) {
                if (c <= '9') hexnum = (c - '0') << 4;
                else hexnum = (10 +(c - 'A')) << 4;
                digit = 2;
            }
            else if (digit == 2) {
                if (c <= '9') hexnum |= (c - '0');
                else hexnum |= 10 + (c - 'A');
                temp[p++] = (unsigned char)hexnum;
                digit = 0;
            }
            else temp[p++] = c;
        }
    }
    temp[p++] = 0;
    memcpy (oldstring, temp, p);
    free (temp);
}

static void AddSimParam (char *name, char *string)
{
    long i;

    // ���������, ���� ����� �������� ��� ����������, ������ �������� ��� ��������.
    for (i=0; i<simargc; i++) {
        if (!strcmp (name, simargv[i].name)) {
            strncpy (simargv[i].string, string, sizeof(simargv[i].string));
            simargv[i].value = strtoul (simargv[i].string, NULL, 10);
            return;
        }
    }

    // �������� ����� ��� ����� �������� � �������� ��������.
    hexize (string);
    simargv = (SimParam *)realloc (simargv, (simargc + 1) * sizeof (SimParam) );
    strncpy (simargv[simargc].name, name, sizeof (simargv[simargc].name) );
    strncpy (simargv[simargc].string, string, sizeof (simargv[simargc].string) );
    simargv[simargc].value = strtoul (simargv[simargc].string, NULL, 10);
    simargc ++;
}

static void PrintParams (void)
{
    long i;
    SimParam *p;
    for (i=0; i<simargc; i++) {
        p = &simargv[i];
        printf ( "%i: %s = %s (%i)<br>\n", i, p->name, p->string, p->value );
    }
    printf ("<hr/>");
}

// ��������� ���������.
static void ParseQueryString (char *str)
{
    int collectname = 1;
    char namebuffer[100], stringbuffer[100], c;
    long length, namelen = 0, stringlen = 0;
    memset (namebuffer, 0, sizeof(namebuffer));
    memset (stringbuffer, 0, sizeof(stringbuffer));
    if (str == NULL) return;
    length = (long)strlen (str);
    while (length--) {
        c = *str++;
        if ( c == '=' ) {
            collectname = 0;
        }
        else if (c == '&') { // �������� ��������.
            collectname = 1;
            if (namelen >0 && stringlen > 0) {
                AddSimParam (namebuffer, stringbuffer);
            }
            memset (namebuffer, 0, sizeof(namebuffer));
            memset (stringbuffer, 0, sizeof(stringbuffer));
            namelen = stringlen = 0;
        }
        else {
            if (collectname) {
                if (namelen < 31) namebuffer[namelen++] = c;
            }
            else {
                if (stringlen < 63) stringbuffer[stringlen++] = c;
            }
        }
    }
    // �������� ��������� ��������.
    if (namelen > 0 && stringlen > 0) AddSimParam (namebuffer, stringbuffer);
}

static SimParam *ParamLookup (char *name)
{
    SimParam *p = NULL;
    long i;
    for (i=0; i<simargc; i++) {
        if (!strcmp (simargv[i].name, name)) return &simargv[i];
    }
    return p;
}

static int GetSimParamI (char *name, int def)
{
    SimParam *p = ParamLookup (name);
    if (p == NULL) return def;
    else return p->value;
}

static char *GetSimParamS (char *name, char *def)
{
    SimParam *p = ParamLookup (name);
    if (p == NULL) return def;
    else return p->string;
}

/*

������ ������� ������
������� ������ �������� �������� ��������� ����� � ��������� �������. ��� �������� ������� � ��, �������� ������������ � ������� "���������� = ��������".

Rapidfire = 1
FID = 30
DID = 0
Attackers = N
Defenders = M
AttackerN = (ID WEAP SHLD ARMR MT BT LF HF CR LINK COLON REC SPY BOMB SS DEST DS BC)
DefenderM = (ID WEAP SHLD ARMR MT BT LF HF CR LINK COLON REC SPY BOMB SS DEST DS BC RT LL HL GS IC PL SDOM LDOM)

*/

void StartBattle (char *text)
{
    char filename[1024];
    Slot *a, *d;
    int rf, fid, did, i, res;
    int anum = 0, dnum = 0;
    char *ptr, line[1000], buf[64], *lp;

    ptr = strstr (text, "Rapidfire");       // ����������
    if ( ptr ) {
        ptr = strstr ( ptr, "=" ) + 1;
        rf = atoi (ptr);
    }
    else rf = 1;

    ptr = strstr (text, "FID");             // ���� � �������
    if ( ptr ) {
        ptr = strstr ( ptr, "=" ) + 1;
        fid = atoi (ptr);
    }
    else fid = 30;

    ptr = strstr (text, "DID");             // ������� � �������
    if ( ptr ) {
        ptr = strstr ( ptr, "=" ) + 1;
        did = atoi (ptr);
    }
    else did = 0;

    ptr = strstr (text, "Attackers");        // ���������� ���������
    if ( ptr ) {
        ptr = strstr ( ptr, "=" ) + 1;
        anum = atoi (ptr);
    }
    else anum = 0;
    ptr = strstr (text, "Defenders");        // ���������� �������������
    if ( ptr ) {
        ptr = strstr ( ptr, "=" ) + 1;
        dnum = atoi (ptr);
    }
    else dnum = 0;

    if ( anum == 0 || dnum == 0) return;

    a = (Slot *)malloc ( anum * sizeof (Slot) );    // �������� ������ ��� �����.
    memset ( a, 0, anum * sizeof (Slot) );
    d = (Slot *)malloc ( dnum * sizeof (Slot) );
    memset ( d, 0, dnum * sizeof (Slot) );

    // ���������.
    for (i=0; i<anum; i++)
    {
        sprintf ( buf, "Attacker%i", i );
        ptr = strstr (text, buf);
        if ( ptr ) {
            lp = line;
            ptr = strstr ( ptr, "=" ) + 1;
            while ( *ptr != '(' ) ptr++;
            ptr++;
            while ( *ptr != ')' ) *lp++ = *ptr++;
            *lp++ = 0;
        }

        // (ID WEAP SHLD ARMR trif recy spio storm raider tarni kolo inva cf zerri sterni khs ghs luna solsat imptran empb wardr)
        sscanf ( line, "%f " "%i %i %i %i" "%i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i", 
                       &a[i].bonus, 
                       &a[i].weap, &a[i].shld, &a[i].armor, &a[i].hitquote,
                       &a[i].fleet[0], // trif
                       &a[i].fleet[1], // recy
                       &a[i].fleet[2], // spio
                       &a[i].fleet[3], // storm
                       &a[i].fleet[4], // raider
                       &a[i].fleet[5], // tarni
                       &a[i].fleet[6], // kolo
                       &a[i].fleet[7], // inva
                       &a[i].fleet[8], // cf
                       &a[i].fleet[9], // zerri
                       &a[i].fleet[10], // sterni
                       &a[i].fleet[11], // khs
                       &a[i].fleet[12], // ghs
                       &a[i].fleet[13], // luna
                       &a[i].fleet[14], // solsat
                       &a[i].fleet[15], // impstrans
                       &a[i].fleet[16], // empbom
                       &a[i].fleet[17] ); // wardrainer
    }

    // �������������.
    for (i=0; i<dnum; i++)
    {
        sprintf ( buf, "Defender%i", i );
        ptr = strstr (text, buf);
        if ( ptr ) {
            lp = line;
            ptr = strstr ( ptr, "=" ) + 1;
            while ( *ptr != '(' ) ptr++;
            ptr++;
            while ( *ptr != ')' ) *lp++ = *ptr++;
            *lp++ = 0;
        }

        // (ID WEAP SHLD ARMR MT trif recy spio storm raider tarni kolo inva cf zerri sterni khs ghs luna solsat imptran empb wardr RT LL HL GS IC PL SDOM LDOM)
        sscanf ( line, "%f " "%i %i %i %i" "%i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i %i", 
                       &d[i].bonus, 
                       &d[i].weap, &d[i].shld, &d[i].armor, &d[i].hitquote,
                       &d[i].fleet[0], // tri
                       &d[i].fleet[1], // recy
                       &d[i].fleet[2], // spio
                       &d[i].fleet[3], // storm
                       &d[i].fleet[4], // raider
                       &d[i].fleet[5], // tarnb
                       &d[i].fleet[6], // kolo
                       &d[i].fleet[7], // inva
                       &d[i].fleet[8], // cf
                       &d[i].fleet[9], // zerri
                       &d[i].fleet[10], // sterni
                       &d[i].fleet[11], // khs
                       &d[i].fleet[12], // ghs
                       &d[i].fleet[13], // luna
                       &d[i].fleet[14], // solsat
                       &d[i].fleet[15], // imptrans
                       &d[i].fleet[16], // emp
                       &d[i].fleet[17], // war
                       &d[i].def[0], // rak
                       &d[i].def[1], // LL
                       &d[i].def[2], // sL
                       &d[i].def[3], // ekan
                       &d[i].def[4], // empw
                       &d[i].def[5], // plasma
                       &d[i].def[6], // nukl
                       &d[i].def[7] // mikro
                ); 
    }

    // ��������� ������� ������.
    SetDebrisOptions ( did, fid );
    SetRapidfire ( rf );

    // **** ������ ����� ****
    res = DoBattle ( a, anum, d, dnum );

    // �������� ���������� � ��.
    
    /*if ( res > 0 )
    {
        sprintf ( filename, "battleresult/battle_%s.txt", battle_id );
        FileSave ( filename, ResultBuffer, strlen (ResultBuffer) );
    }*/
    
    //return ResultBuffer;
}
/*
void main(int argc, char **argv)
{
    char filename[1024];
    char *battle_data;
    char *battle_id;
    
    if ( argc < 2 ) return;

	ParseQueryString ( argv[1] );
	//PrintParams ();

	// ����������� � ����� ������ � ������� �������� ������.
    {
        battle_id = GetSimParamS("battle_id", "notFound");
        
        if ( battle_id == 0 ) return;

        sprintf ( filename, "battledata/battle_%s.txt", battle_id );
        battle_data = FileLoad ( filename, NULL, "rt" );

        // ��������� �������� ������ � �������� ������ � ������ �����.
        MySrand ((unsigned long)time(NULL));
        //StartBattle ( battle_data, battle_id );
    }
}
*/



PHP_FUNCTION(GiCoSys_ComputeBattle)
{
    char *battledata;
    char *battleresult;
    int battledata_length;

    if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "s", &battledata, &battledata_length) == FAILURE) {
        RETURN_STRING("{'error' : 1}",1);
    }
    
    StartBattle(battledata);
    
    RETURN_STRING(ResultBuffer, 1);
}