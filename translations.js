const fs = require('fs');
const path = require('path');

const projectPath = 'app/http/Livewire'; // Cambia esta ruta a la ubicación de tu proyecto Laravel
const translationStrings = {};

function extractTranslationStrings(content) {
  const regex = /{{ __\('(.*?)'\) }}/g;
  const matches = content.match(regex);
  const regex2 = /__\('(.*?)'\)/g;
  const matches2 = content.match(regex2);

  if (matches) {
    matches.forEach(match => {
      const translationKey = match.match(/__\('(.*?)'\)/)[1];
      translationStrings[translationKey] = '';
    });
  }

  if (matches2) {
    matches2.forEach(match => {
      const translationKey = match.match(/__\('(.*?)'\)/)[1];
      translationStrings[translationKey] = '';
    });
  }
}

function traverseDirectory(directoryPath) {
  const files = fs.readdirSync(directoryPath);

  files.forEach(file => {
    const filePath = path.join(directoryPath, file);
    const fileStat = fs.statSync(filePath);

    if (fileStat.isDirectory()) {
      traverseDirectory(filePath);
    } else if (file.endsWith('.php')) {
      const content = fs.readFileSync(filePath, 'utf8');
      extractTranslationStrings(content);
    }
  });
}

traverseDirectory(projectPath);

const caseInsensitiveMap = new Map();

// Convertir las claves a minúsculas para evitar duplicados insensibles a mayúsculas y minúsculas
Object.keys(translationStrings).forEach(key => {
  const lowerCaseKey = key.toLowerCase();
  if (!caseInsensitiveMap.has(lowerCaseKey)) {
    caseInsensitiveMap.set(lowerCaseKey, key);
  }
});

const sortedTranslationStrings = {};
caseInsensitiveMap.forEach((value, key) => {
  sortedTranslationStrings[value] = translationStrings[value];
});

const outputFilePath = path.join(__dirname, 'translations_keys.json');

fs.writeFileSync(outputFilePath, JSON.stringify(sortedTranslationStrings, null, 2), 'utf8');

console.log(`Archivo JSON de cadenas de traducción únicas y ordenado generado en: ${outputFilePath}`);
