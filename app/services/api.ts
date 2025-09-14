// app/services/api.ts
import axios from "axios";

// Change this to your Laravel backend URL
const API_URL = "http://192.168.1.6:8000/api"; 
// ⚠️ Gamitin mo yung LAN IP ng backend mo para ma-access ng phone (Expo Go).
// Huwag 'localhost' kasi hindi makikita ng phone.

const api = axios.create({
  baseURL: API_URL,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Example: Fetch Inventory
export const getInventory = async () => {
  const res = await api.get("/inventory");
  return res.data;
};

// Example: Add Product
export const addProduct = async (product: {
  name: string;
  category_id: number;
  stock: number;
  price: number;
}) => {
  const res = await api.post("/products", product);
  return res.data;
};

// Example: Get Sales Report
export const getSalesReport = async () => {
  const res = await api.get("/reports/sales");
  return res.data;
};

export default api;
