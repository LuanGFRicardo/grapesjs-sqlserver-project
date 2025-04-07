const express = require('express');
const app = express();
const path = require('path');

// Permitir CORS para evitar bloqueios ao carregar HTML
app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
    next();
  });

// Apenas serve os assets corretamente sem interferir no GrapesJS
// app.use('/assets', express.static(path.join(__dirname, 'CRCPR')));

// Servir arquivos da pasta public
app.use(express.static(path.join(__dirname, 'assets')));

const PORT = 8081; // Use uma porta diferente para não interferir no GrapesJS
app.listen(PORT, () => {
    console.log(`Servidor de assets rodando em http://localhost:${PORT}/assets`);
});
