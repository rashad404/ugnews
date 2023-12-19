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
            },
            {
                code: "EUR",
                name: "1 Avro",
                value: 1.8568,
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
        total += "<tr>";
        total += `<td>${item.name}</td>`
        total += `<td>${item.code}</td>`
        total += `<td>${item.value} AZN</td>`
        total += `</tr>`
        return total
    }, "")
}

