import pandas as pd

# pa cargar el excel es:
libro = pd.read_excel('/Data/estado_operaciones.xlsx', sheet_name='1234')

promJulio = libro['Julio'].mean()
promJulio = promJulio * 100
print (promJulio)

promJulio.to_json('promedio.json', orient='records')


