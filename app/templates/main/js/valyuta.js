const valyutaData = {
    body: {
        metal: [
            {
                code: "XPD",
                name: "Palladium",
                value: 2010.2245,
                nominal: "1 t.u"
            },
            {
                code: "XAU",
                name: "Qızıl",
                value: 3439.593,
                nominal: "1 t.u"
            }
        ],
        note: [
            {
                code: "USD",
                name: "1 ABŞ dolları",
                value: 1.69,
                change: "constant"
            },
            {
                code: "EUR",
                name: "1 Avro",
                value: 1.8568,
                change: "increased"
            },
        ]
    },
    date: "15.12.2023",
    name: "AZN məzənnələri",
    description: "Azerbaycan Respublikasi Merkezi Bank mezennesi",
}
const { value: usd } = valyutaData.body.note.find(item => item.code = "USD");
const { note } = valyutaData.body;

const valyutaElement = document.querySelector(".valyuta");
const valyutaTable = document.getElementById("valyuta-table");
if (valyutaElement) valyutaElement.innerHTML = `1 USD = ${usd} AZN`;
if (valyutaTable) {
    valyutaTable.innerHTML += note.reduce((total, item) => {
        total += `<tr>
        <td>
        <div class="d-flex">
        <img src="/app/templates/main/img/currency/${item.code.toLowerCase()}.png" />
        <p class="ps-2">
        ${item.name}
        </p>
        </div>
        </td>
            <td class="fw-semibold">${item.code}</td>
            <td class="fw-semibold">${item.value}</td>
            <td><i class="fa-solid ${item.change == "increased" ? "fa-chevron-up" : item.change == "decreased" ? "fa-chevron-down" : "fa-circle"}"></i></td>
                </tr>`
        return total
    }, "")
}

console.log('sadasd');
