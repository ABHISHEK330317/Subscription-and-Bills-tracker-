import "./App.css";
import { BrowserRouter, Route, Routes } from "react-router-dom";
import Login from "./pages/Login";
import Registration from "./pages/Registration";
import Dashboard from "./pages/Dashboard";
import ManageSubscriptions from "./pages/ManageSubscriptions";
import Reminder from "./pages/Reminder";
import NotFound from "./pages/NotFound";

export const baseurl = "https://yhmysore.in/api/billAPI.php"

function App() {
  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Login />} />
          <Route path="/register" element={<Registration />} />
          <Route path="/dashboard" element={<Dashboard />}>
            <Route path="manage" element={<ManageSubscriptions />} />
            <Route path="" element={<Reminder />} />
            <Route path="*" element={<NotFound />} />
          </Route>
        </Routes>
      </BrowserRouter>
    </>
  );
}

export default App;
