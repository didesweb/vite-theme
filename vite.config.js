import { defineConfig } from "vite";
import fs from "fs";
import path from "path";

export default defineConfig({
  root: "src",
  base: "",
  server: {
    cors: true,
    strictPort: true,
    port: 5173,
    watch: {
      usePolling: true,
      interval: 100
    }
  },

  css: {
    preprocessorOptions: {
      scss: {
        api: "modern-compiler"
      }
    }
  },

  plugins: [
    {
      name: "php-reload",
      configureServer(server) {
        const themePath = path.resolve(process.cwd());

        fs.watch(themePath, { recursive: true }, (event, file) => {
          if (file.endsWith(".php")) {
            console.log("🔁 Recargando por cambio en:", file);
            server.ws.send({
              type: "full-reload",
              path: "*"
            });
          }
        });
      }
    }
  ],

build: {
  outDir: "../dist",
  emptyOutDir: true,
  manifest: true,
  assetsDir: "assets",
  rollupOptions: {
    input: path.resolve(__dirname, "src/js/main.js")
  }
}

});
