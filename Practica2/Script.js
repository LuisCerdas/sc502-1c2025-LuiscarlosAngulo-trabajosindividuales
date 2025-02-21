function calculateDeducciones() {
    const salarioBruto = parseFloat(document.getElementById('salarioBruto').value);
    const resultsDiv = document.getElementById('results');

    if (isNaN(salarioBruto) || salarioBruto <= 0) {
        resultsDiv.innerHTML = '<p style="color: red;">Por favor, ingrese un salario válido.</p>';
        return;
    }

    // Cálculo de las deducciones de cargas sociales
    const ccss = salarioBruto * 0.1067;  // (10.67%)
    const banco = salarioBruto * 0.01;   // Banco Popular (1%)

    const totalDeduccionesSociales = ccss + banco;

    // Cálculo del impuesto sobre la renta
    let impuesto = 0;
    const montoMensual = 863000; // Monto exento mensual 
    const salarioImpuesto = salarioBruto - montoMensual;

    if (salarioImpuesto  > 0) {
        if (salarioImpuesto <= 1257000) {
            impuesto  = salarioImpuesto * 0.10;
        } else if (salarioImpuesto <= 2223000) {
            impuesto  = 125700 + (salarioImpuesto - 1257000) * 0.15;
        } else if (salarioImpuesto <= 4445000) {
            impuesto  = 270600 + (salarioImpuesto - 2223000) * 0.20;
        } else {
            impuesto  = 715000 + (salarioImpuesto - 4445000) * 0.25;
        }
    }

    const totalDeducciones = totalDeduccionesSociales + impuesto ;
    const salarioNeto = salarioBruto - totalDeducciones;

    resultsDiv.innerHTML = `
        <h3>Resultados:</h3>
        <p><strong>Deducciones de Cargas Sociales:</strong></p>
        <ul>
            <li>CCSS (10.67%): ₡${ccss.toFixed(2)}</li>
            <li>Banco Popular (1%): ₡${banco.toFixed(2)}</li>
        </ul>
        <p><strong>Total Cargas Sociales:</strong> ₡${totalDeduccionesSociales.toFixed(2)}</p>
        <p><strong>Impuesto sobre la Renta:</strong> ₡${iimpuesto.toFixed(2)}</p>
        <p><strong>Total Deducciones:</strong> ₡${totalDeductions.toFixed(2)}</p>
        <p><strong>Salario Neto:</strong> ₡${salarioNeto.toFixed(2)}</p>
    `;
}