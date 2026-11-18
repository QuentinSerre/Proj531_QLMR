import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
// import './styles/mapage.css'
// import App from './App.jsx'
import Mapage from './mapage.jsx'

createRoot(document.getElementById('root')).render(
  <StrictMode>
    <Mapage />
  </StrictMode>,
)
