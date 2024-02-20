SELECT *
FROM (
select distinct
NumeroParte AS PARTE,
descripcion AS DESCRIPCION,
Bodega AS BODEGA,
CONVERT(DATETIME, FechaCreacion, 103) AS FechaCreacion,
UltimaCompra,
CONVERT(DATETIME, UltimaVenta, 103) AS UltimaVenta,
DATEDIFF(DAY, CONVERT(DATETIME, UltimaVenta, 103), GETDATE()) AS UltimaVentaDias,
CAST(Existencia as int) AS EXISTENCIAS,
CostoUnitario$ AS COSTOUNIDAD,
Costo$ AS COSTOTOTAL,
ubicacion
from REFINV01_2021_FORD
union all 
select distinct
NumeroParte AS PARTE,
descripcion AS DESCRIPCION,
Bodega AS BODEGA,
CONVERT(DATETIME, FechaCreacion, 103) AS FechaCreacion,
UltimaCompra,
CONVERT(DATETIME, UltimaVenta, 103) AS UltimaVenta,
DATEDIFF(DAY, CONVERT(DATETIME, UltimaVenta, 103), GETDATE()) AS UltimaVentaDias,
CAST(Existencia as int) AS EXISTENCIAS,
CostoUnitario$ AS COSTOUNIDAD,
Costo$ AS COSTOTOTAL,
ubicacion
from REFINV01_2021_FOTON
union all 
select distinct
NumeroParte AS PARTE,
descripcion AS DESCRIPCION,
Bodega AS BODEGA,
CONVERT(DATETIME, FechaCreacion, 103) AS FechaCreacion,
UltimaCompra,
CONVERT(DATETIME, UltimaVenta, 103) AS UltimaVenta,
DATEDIFF(DAY, CONVERT(DATETIME, UltimaVenta, 103), GETDATE()) AS UltimaVentaDias,
CAST(Existencia as int) AS EXISTENCIAS,
CONVERT(numeric(11,4),CostoUnitario$) AS COSTOUNIDAD,
Costo$ AS COSTOTOTAL,
ubicacion
from REFINV01_2021_FCA
union all 
select distinct
NumeroParte AS PARTE,
descripcion AS DESCRIPCION,
Bodega AS BODEGA,
CONVERT(DATETIME, FechaCreacion, 103) AS FechaCreacion,
UltimaCompra,
CONVERT(DATETIME, UltimaVenta, 103) AS UltimaVenta,
DATEDIFF(DAY, CONVERT(DATETIME, UltimaVenta, 103), GETDATE()) AS UltimaVentaDias,
CAST(Existencia as int) AS EXISTENCIAS,
CONVERT(numeric(11,4),CostoUnitario$) AS COSTOUNIDAD,
Costo$ AS COSTOTOTAL,
ubicacion
from REFINV01_2021_PEUGEOT
union all 
select distinct
NumeroParte AS PARTE,
descripcion AS DESCRIPCION,
Bodega AS BODEGA,
CONVERT(DATETIME, FechaCreacion, 103) AS FechaCreacion,
UltimaCompra,
CONVERT(DATETIME, UltimaVenta, 103) AS UltimaVenta,
DATEDIFF(DAY, CONVERT(DATETIME, UltimaVenta, 103), GETDATE()) AS UltimaVentaDias,
CAST(Existencia as int) AS EXISTENCIAS,
CONVERT(numeric(11,4),CostoUnitario) AS COSTOUNIDAD,
Costo AS COSTOTOTAL,
ubicacion
from REFINV01_2021_BAJAJ
) AS D
ORDER BY UltimaVentaDias asc
