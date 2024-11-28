import React from 'react';
import ReactDOM from 'react-dom/client';
import CreateSnippetForm from './components/CreateSnippetForm';
import Home from './components/Home';

const homeRoot = document.getElementById("home");
if (homeRoot) {
    ReactDOM.createRoot(homeRoot).render(<Home user={window.__USER__} />);
}

const createSnippetRoot = document.getElementById('create-snippet');
if (createSnippetRoot) {
    ReactDOM.createRoot(createSnippetRoot).render(<CreateSnippetForm />);
}
