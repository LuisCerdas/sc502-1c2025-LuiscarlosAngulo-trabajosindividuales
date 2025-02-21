// Array de productos
let productos = [];

// Elementos del DOM
const formularioProducto = document.getElementById('formulario-producto');
const listaProductos = document.getElementById('lista-productos');
const filtroCategoria = document.getElementById('filtro-categoria');
const inputIdProducto = document.getElementById('id-producto'); 

// Agregar un producto
function agregarProducto(nombre, precio, categoria) {
    const producto = {
        id: Date.now(), 
        nombre,
        precio,
        categoria
    };
    productos.push(producto);
    renderizarProductos();
}

// Eliminar un producto
function eliminarProducto(id) {
    productos = productos.filter(producto => producto.id !== id);
    renderizarProductos();
}

// Editar un producto
function editarProducto(id) {
    const producto = productos.find(producto => producto.id === id);
    if (producto) {
        // Rellenamos el formulario con los datos del producto seleccionado
        document.getElementById('nombre-producto').value = producto.nombre;
        document.getElementById('precio-producto').value = producto.precio;
        document.getElementById('categoria-producto').value = producto.categoria;
        inputIdProducto.value = producto.id; 
    }
}

// Función para mostrar los productos en el DOM
function renderizarProductos() {
    const categoriaSeleccionada = filtroCategoria.value;
    
    // Filtramos los productos por categoría si se ha seleccionado una
    const productosFiltrados = categoriaSeleccionada
        ? productos.filter(producto => producto.categoria === categoriaSeleccionada)
        : productos;

    listaProductos.innerHTML = '';
    productosFiltrados.forEach(producto => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        li.innerHTML = `
            ${producto.nombre} - $${producto.precio.toFixed(2)} - ${producto.categoria}
            <button class="btn btn-warning btn-sm" onclick="editarProducto(${producto.id})">Editar</button>
            <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">Eliminar</button>
        `;
        listaProductos.appendChild(li);
    });
}

// Event listener para el formulario de agregar/editar producto
formularioProducto.addEventListener('submit', function(e) {
    e.preventDefault();
    const nombre = document.getElementById('nombre-producto').value;
    const precio = parseFloat(document.getElementById('precio-producto').value);
    const categoria = document.getElementById('categoria-producto').value;
    const idProducto = inputIdProducto.value; // ID del producto si se está editando

    if (idProducto) {
        // Si existe el ID, editamos el producto
        const producto = productos.find(producto => producto.id == idProducto);
        if (producto) {
            producto.nombre = nombre;
            producto.precio = precio;
            producto.categoria = categoria;
        }
    } else {
        agregarProducto(nombre, precio, categoria);
    }

    formularioProducto.reset();
    inputIdProducto.value = ''; 
    renderizarProductos();
});

// Filtro de categoría
filtroCategoria.addEventListener('change', renderizarProductos);

// Renderizamos los productos inicialmente
renderizarProductos();
