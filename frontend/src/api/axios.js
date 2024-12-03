import axios from "axios";

const axiosInstance = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
});

axiosInstance.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");

  // Add token to headers
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  // Check if data is an instance of FormData, and add Content-Type to headers
  if (config.data instanceof FormData) {
    config.headers["Content-Type"] = "multipart/form-data";
  } else {
    config.headers["Content-Type"] = "application/json";
  }

  return config;
});

export default axiosInstance;
