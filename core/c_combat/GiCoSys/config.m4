PHP_ARG_ENABLE(GiCoSys, whether to enable GiCoSys,
[ --enable-gicosys   Enable GiCoSys])
 
if test "$PHP_GICOSYS" = "yes"; then
  AC_DEFINE(HAVE_GICOSYS, 1, [Whether you have GiCoSys])
  PHP_NEW_EXTENSION(GiCoSys, GiCoSys.c, $ext_shared)
fi