SELECT 
    hc.id AS historico_id,
    c.nome_completo AS cliente,
    
    v.modelo AS carro,
    v.ano,
    
    m.nome AS marca,
    
    hc.data_compra,
    hc.preco_compra,
    hc.metodo_pagamento,
    hc.observacoes

FROM historico_compras hc
JOIN clientes c ON hc.cliente_id = c.id
JOIN veiculos v ON hc.carro_id = v.id
JOIN marcas m ON v.id_marca = m.id

ORDER BY hc.data_compra DESC;



SELECT 
    hc.id AS historico_id,
    c.nome_completo AS cliente,
    
    v.modelo AS carro,
    v.ano,
    m.nome AS marca,
    
    hc.data_compra,
    hc.preco_compra,
    hc.metodo_pagamento

FROM historico_compras hc
JOIN clientes c ON hc.cliente_id = c.id
JOIN veiculos v ON hc.carro_id = v.id
JOIN marcas m ON v.id_marca = m.id

WHERE c.id = 16
ORDER BY hc.data_compra DESC;