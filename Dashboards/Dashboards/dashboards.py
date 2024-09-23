import pandas as pd

import pandas as pd
import numpy as np

masterImports = pd.read_excel('Data/DETALLE SAINT GOBAIN_copia.xlsx', sheet_name='ENTRADAS 2024')

totalCajas = int(masterImports['CAJAS'].sum())
totalCBM = float(masterImports['CBM'].sum())
totalPaletas = int(masterImports['Total Paletas'].sum())
palSKU = int(masterImports[ 'PALETAS 1 SKU'].sum())


print('Total de cajas: ', totalCajas)	
print('Total de CBM: ',  totalCBM)
print('Total de Paletas: ', totalPaletas)