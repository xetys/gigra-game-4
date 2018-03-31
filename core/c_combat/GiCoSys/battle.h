// ������� �����.
#ifndef WIN32
#include <linux/types.h>
typedef __u64 u64;
#else
#include <windows.h>
typedef unsigned __int64 u64;
#endif

typedef struct TechParam {
    long    structure;
    long    shield;
    long    attack;
    long    cargo;  // ������ ��� �����.
    long    emp;
} TechParam;

// ������ �����.
typedef struct Slot
{
    unsigned    long fleet[18];         // ����
    unsigned    long def[8];            // �������
    int         weap, shld, armor, hitquote;      // ����������
    int         id;                     // ID ������� / �����
    float       bonus;
} Slot;

// ������ �����.
typedef struct Unit {
    unsigned char slot_id;
    unsigned char obj_type;
    unsigned char exploded;
    unsigned char dummy;                // ��� ������������ ��������� �� 4 �����.
    long    hull, hullmax;
    long    shield, shieldmax;
    long    angbonus;
} Unit;

extern TechParam fleetParam[18];
extern TechParam defenseParam[8];
