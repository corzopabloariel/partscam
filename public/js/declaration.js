const ENTIDADES = {
    empresa_email: {
        ATRIBUTOS: {
            email: {TIPO:"TP_EMAIL",MAXLENGTH:150,VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                email: '<div class="col-12">/email/</div>'
            }
        ]
    },
    empresa_telefono: {
        ATRIBUTOS: {
            telefono: {TIPO:"TP_PHONE",MAXLENGTH:30,VISIBILIDAD:"TP_VISIBLE"},
            tipo: {TIPO:"TP_ENUM",ENUM:{tel:"Teléfono",cel:"Celular",wha:"Whatsapp"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"text-uppercase",NOMBRE:"Tipo"}
        },
        FORM: [
            {
                tipo: '<div class="col-5">/tipo/</div>',
                telefono: '<div class="col-7">/telefono/</div>',
            }
        ]
    },
    empresa_domicilio: {
        ATRIBUTOS: {
            calle: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE"},
            altura: {TIPO:"TP_ENTERO",VISIBILIDAD:"TP_VISIBLE"},
            barrio: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                calle: '<div class="col-12 col-md-8">/calle/</div>',
                altura: '<div class="col-4">/altura/</div>',
            },
            {
                barrio: '<div class="col-12 col-md-6">/barrio/</div>'
            }
        ]
    },
    empresa_images: {
        ATRIBUTOS: {
            logo: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Logotipo OK",INVALID:"Logotipo - 205x100",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"250px"},
            logoFooter: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Logotipo Footer OK",INVALID:"Logotipo Footer - 503x223",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"250px"},
            favicon: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Favicon OK",INVALID:"Favicon",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/x-icon,image/png",NOMBRE:"imagen",WIDTH:"250px"},
        },
        FORM: [
            {
                logo: '<div class="col-7 col-md-4">/logo/</div>',
                logoFooter: '<div class="col-5 col-md-5">/logoFooter/</div>',
                favicon: '<div class="col-3 col-md-3">/favicon/</div>'
            }
        ],
        FUNCIONES: {
            logo: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            logoFooter: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            favicon: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    home: {
        ATRIBUTOS: {
            page: {TIPO:"TP_ENUM",ENUM:{slider:"Slider",marcas:"Marcas",familias:"Familias",buscador: "Buscador", ofertas: "Ofertas", entrega: "Entrega",mercadopago:"Mercadopago"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"text-uppercase",NOMBRE:"secciones",MULTIPLE: 1},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo OK",INVALID:"Archivo - 120x76",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"250px"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        JSON: {
            texto: {
                es: "español"
            },
        },
        FORM: [
            {
                page: '<div class="col-7 col-md-6">/page/</div>',
                BTN: '<div class="d-flex col-3 col-md-2">/BTN/</div>'
            },
            {
                texto: '<div class="col-12 col-md-9">/texto/</div>',
                image: '<div class="col-12 col-md-3">/image/</div>',
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    empresa: {
        ATRIBUTOS: {
            page: {TIPO:"TP_ENUM",ENUM:{slider:"Slider",mercadopago:"Mercadopago"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"text-uppercase",NOMBRE:"secciones",MULTIPLE: 1},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo OK",INVALID:"Archivo - 396x290",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"250px"},
            titulo_empresa: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título empresa"},
            texto_empresa: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto empresa"},
            titulo_filosofia: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título filosofía"},
            texto_filosofia: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto filosofía"}
        },
        JSON: {
            texto_empresa: {
                es: "español"
            },
            texto_filosofia: {
                es: "español"
            },
            titulo_empresa: {
                es: "español"
            },
            titulo_filosofia: {
                es: "español"
            },
        },
        FORM: [
            {
                BTN: '<div class="d-flex col-3 col-md-2">/BTN/</div>'
            },
            {
                page: '<div class="col-7 col-md-6">/page/</div>',
                image: '<div class="col-12 col-md-4">/image/</div>',
            },
            {
                titulo_empresa: '<div class="col-12">/titulo_empresa/</div>',
                texto_empresa: '<div class="col-12 mt-2">/texto_empresa/</div>',
            },
            {
                titulo_filosofia: '<div class="col-12">/titulo_filosofia/</div>',
                texto_filosofia: '<div class="col-12 mt-2">/texto_filosofia/</div>',
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    terminos: {
        ATRIBUTOS: {
            titulo: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        JSON: {
            titulo: {
                es: "español"
            },
            texto: {
                es: "español"
            },
        },
        FORM: [
            {
                BTN: '<div class="d-flex col-3 col-md-2">/BTN/</div>'
            },
            {
                titulo: '<div class="col-12">/titulo/</div>',
                texto: '<div class="col-12 mt-2">/texto/</div>',
            }
        ]
    },
    pagos: {
        ATRIBUTOS: {
            page: {TIPO:"TP_ENUM",ENUM:{slider:"Slider"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"text-uppercase",NOMBRE:"secciones",MULTIPLE: 1},
            titulo: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"título"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        JSON: {
            texto: {
                es: "español"
            },
            titulo: {
                es: "español"
            },
        },
        FORM: [
            {
                page: '<div class="col-7 col-md-6">/page/</div>',
                BTN: '<div class="d-flex col-3 col-md-2">/BTN/</div>'
            },
            {
                titulo: '<div class="col-12">/titulo/</div>',
                texto: '<div class="col-12 mt-2">/texto/</div>'
            }
        ]
    },
    usuario: {
        ATRIBUTOS: {
            username: {TIPO:"TP_STRING",MAXLENGTH:30,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"usuario"},
            name: {TIPO:"TP_STRING",MAXLENGTH:100,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase",NOMBRE:"nombre"},
            password: {TIPO:"TP_PASSWORD",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"contraseña"},
            is_admin: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{1:"Administrador",0:"Usuario"},NOMBRE:"Tipo",CLASS:"text-uppercase"},
        },
        FORM: [
            {
                name: '<div class="col-7 col-md-6">/name/</div>',
                is_admin: '<div class="col-5 col-md-4">/is_admin/</div>',
                BTN: '<div class="d-flex col-3 col-md-2">/BTN/</div>'
            },
            {
                username: '<div class="col-6">/username/</div>',
                password: '<div class="col-6">/password/</div>',
            }
        ],
    },
    slider: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"150px"},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione archivo - 1400x450",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"250px"},
            seccion: {TIPO:"TP_ENUM",ENUM:{home:"Home",empresa:"Empresa",ofertas:"Ofertas",pagos: "Pagos y envios"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"text-uppercase",NOMBRE:"sección"},
            texto: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"texto"}
        },
        JSON: {
            texto: {
                es: "español"
            },
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-3">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                image: '<div class="col-12 col-md-6">/image/</div>',
            },
            {
                texto: '<div class="col-12">/texto/</div>'
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    usuarios: {
        ATRIBUTOS: {
            username: {TIPO:"TP_STRING",MAXLENGTH:30,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"usuario"},
            name: {TIPO:"TP_STRING",MAXLENGTH:100,NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"nombre"},
            password: {TIPO:"TP_PASSWORD",VISIBILIDAD:"TP_VISIBLE_FORM",NOMBRE:"contraseña"},
            is_admin: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{1:"Administrador",0:"Usuario"},NOMBRE:"Tipo",CLASS:"text-uppercase"},
        },
        FORM: [
            {
                BTN: '<div class="col-3 col-md-2">/BTN/</div>'
            },
            {
                is_admin: '<div class="col-12 col-md-6">/is_admin/</div>',
            },
            {
                name: '<div class="col-12 col-md-6">/name/</div>',
            },
            {
                username: '<div class="col-3">/username/</div>',
                password: '<div class="col-3">/password/</div>',
            }
        ],
    },
    marca: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"150px"},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione archivo - 151x64",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"151px"},
            nombre: {TIPO:"TP_STRING",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-3">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                nombre: '<div class="col-12 col-md-6">/nombre/</div>'
            },
            {
                image: '<div class="col-12 col-md-6 col-lg-4">/image/</div>',
            },
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    familias: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"150px"},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione archivo - 400x393",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"151px"},
            nombre: {TIPO:"TP_STRING",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE"}
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-3">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                nombre: '<div class="col-12 col-md-6">/nombre/</div>'
            },
            {
                image: '<div class="col-12 col-md-6 col-lg-4">/image/</div>',
            },
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    categorias: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"100px"},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione archivo - 400x393",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"151px"},
            familia_id: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Familia",COMUN:1},
            //padre: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE_TABLE",NOMBRE:"CATEGORÍA",WIDTH:"220px"},
            nombre: {TIPO:"TP_STRING",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE"},
            //padre_id: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE_FORM",DISABLED:1,NOMBRE:"Categoría"}
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-3">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                familia_id: '<div class="col-12 col-md-6">/familia_id/</div>'
            },
            {
                nombre: '<div class="col-12 col-md-6">/nombre/</div>'
            },
            {
                image: '<div class="col-12 col-md-6 col-lg-4">/image/</div>',
            },
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            //familia_id: {onchange: "changeFamilia(this, 1); habilitar(this)"},
            modelo_id: {onchange: "changeFamilia(this, 2)"},
        }
    },
    subcategorias: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"100px"},
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione archivo - 400x393",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE",ACCEPT:"image/*",NOMBRE:"imagen",WIDTH:"151px"},
            //familia: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE_TABLE"},
            //padre: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE_TABLE",NOMBRE:"CATEGORÍA",WIDTH:"220px"},
            nombre: {TIPO:"TP_STRING",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE"},
            //padre_id: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE_FORM",DISABLED:1,NOMBRE:"Categoría"}
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-4">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-4">/BTN/</div>'
            },
            {
                nombre: '<div class="col-12 col-md-8">/nombre/</div>'
            },
            {
                image: '<div class="col-12 col-md-6 col-lg-6">/image/</div>',
            },
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}},
            familia_id: {onchange: "changeFamilia(this, 1); habilitar(this)"},
            modelo_id: {onchange: "changeFamilia(this, 2)"},
        }
    },
    modelos: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"150px"},
            nombre: {TIPO:"TP_STRING",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE"},
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-3">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                nombre: '<div class="col-12 col-md-6">/nombre/</div>'
            }
        ]
    },
    ofertas: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:10,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase text-center",WIDTH:"150px"},
            porcentaje: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",READONLY:1,CLASS:"text-uppercase text-right",WIDTH:"180px"},
            precio: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",DISABLED:1,CLASS:"text-uppercase text-right",WIDTH:"150px"},
            nombre: {TIPO:"TP_STRING",NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"producto"},
            producto: {TIPO:"TP_ENUM",NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_FORM"},
        },
        FORM: [
            {
                orden: '<div class="d-flex col-3 col-md-3">/orden/</div>',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                producto: '<div class="col-12 col-md-6">/producto/</div>',
            },
            {
                precio: '<div class="col-12 col-md-3">/precio/</div>',
                porcentaje: '<div class="col-12 col-md-3">/porcentaje/</div>',
            },
        ],
        FUNCIONES: {
            producto: {onchange: "activarCalculo(this)"},
            precio: {onblur:"calcular(this);"}
        }
    },
    productoscategoria: {
        ATRIBUTOS: {
            categoria_id: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Categoría",DISABLED:1},
        },
        FORM: [
            {
                categoria_id: '<div class="col-12">/categoria_id/</div>',
            },
        ],
        FUNCIONES: {
            categoria_id: {onchange: "changeCategoria(this)"},
        }
    },
    productos: {
        ATRIBUTOS: {
            orden: {TIPO:"TP_STRING",MAXLENGTH:10,VISIBILIDAD:"TP_VISIBLE_FORM",CLASS:"text-uppercase text-center",WIDTH:"150px"},
            codigo: {TIPO:"TP_STRING",MAXLENGTH:20,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-uppercase",WIDTH:"150px",NOMBRE:"código",LABEL:1},
            nombre: {TIPO:"TP_STRING",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE_FORM",LABEL:1},
            aplicacion: {TIPO:"TP_TEXT",EDITOR:1,FIELDSET:1,VISIBILIDAD:"TP_VISIBLE_FORM",NOMBRE:"aplicación"},
            stock: {TIPO:"TP_ENTERO",MAXLENGTH: 100,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-right",LABEL:1},
            precio: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",CLASS:"text-right",LABEL: 1},
            mercadolibre: {TIPO:"TP_STRING",MAXLENGTH: 150,VISIBILIDAD:"TP_VISIBLE_FORM", LABEL:1},
            familia_id: {TIPO:"TP_ENUM",NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Familia"},
            modelo_id: {TIPO:"TP_ENUM",NECESARIO:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Modelo",MULTIPLE:1},
            categoria_id: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Categoría",DISABLED:1},
            relaciones: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE_FORM",NOMBRE:"productos relacionados",MULTIPLE:1},
        },
        FORM: [
            {
                orden: '<div class="col-5 col-md-4">/orden/</div>',
                BTN: '<div class="d-flex col-5 col-md-4">/BTN/</div>'
            },
            {
                codigo: '<div class="col-12 col-md-4">/codigo/</div>',
                stock: '<div class="col-12 col-md-4">/stock/</div>',
                precio: '<div class="col-3 col-md-4">/precio/</div>'
            },
            {
                nombre: '<div class="col-12">/nombre/</div>',
            },
            {
                mercadolibre: '<div class="col-12">/mercadolibre/</div>',
            },
            {
                aplicacion: '<div class="col-12">/aplicacion/</div>',
            },
            {
                relaciones: '<div class="col-12">/relaciones/</div>',
            }
        ],
        FUNCIONES: {
            familia_id: {onchange: "changeFamilia(this,0)"},
            precio: {onkeypress: "permite(event,'.,0123456789/');"},
            stock: {onkeypress: "permite(event,'0123456789')"}
        }
    },
    productoimages: {
        ATRIBUTOS: {
            image: {TIPO:"TP_FILE",NECESARIO:1,VALID:"Archivo seleccionado",INVALID:"Seleccione archivo - 394x394",BROWSER:"Buscar",VISIBILIDAD:"TP_VISIBLE"},
            orden: {TIPO:"TP_STRING",MAXLENGTH:3,VISIBILIDAD:"TP_VISIBLE",CLASS:"text-center text-uppercase"}
        },
        FORM: [
            {
                image: '<div class="col-12">/image/</div>',
                orden: '<div class="col-12 mt-2">/orden/</div>',
            }
        ],
        FUNCIONES: {
            image: {onchange:{F:"readURL(this,'/id/')",C:"id"}}
        }
    },
    metadatos: {
        ATRIBUTOS: {
            seccion: {TIPO:"TP_ENUM",ENUM:{home:"Home",empresa:"Empresa",ofertas:"Ofertas",contacto: "Contacto"},NECESARIO:1,VISIBILIDAD:"TP_VISIBLE_TABLE",CLASS:"text-uppercase",NOMBRE:"sección",WIDTH:"150px"},
            metas: {TIPO:"TP_TEXT",VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"metadatos (,)"},
            descripcion: {TIPO:"TP_TEXT",VISIBILIDAD:"TP_VISIBLE",FIELDSET:1,NOMBRE:"descripción"}
        },
        FORM: [
            {
                seccion: '/seccion/',
                BTN: '<div class="d-flex col-3 col-md-3">/BTN/</div>'
            },
            {
                descripcion: '<div class="col-12">/descripcion/</div>',
                metas: '<div class="col-12 mt-2">/metas/</div>'
            }
        ]
    },
    transacciones: {
        ATRIBUTOS: {
            tipopago: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{"MP":"MercadoPago","TB":"Transferencia Bancaría","PL":"Pago en el Local"}},
            estado: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{1:"Activo",2:"En proceso", 3:"Cancelado"}},
            codigo: {TIPO:"TP_STRING",MAXLENGTH: 25, VISIBILIDAD:"TP_VISIBLE"},
            total: {TIPO: "TP_FLOAT",VISIBILIDAD:"TP_VISIBLE_TABLE"}
        }
    },
    transcaccionesprod: {
        ATRIBUTOS: {
            tipopago: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{"MP":"MercadoPago","TB":"Transferencia Bancaría","PL":"Pago en el Local"}},
            estado: {TIPO:"TP_ENUM",VISIBILIDAD:"TP_VISIBLE",ENUM:{1:"Activo",2:"En proceso", 3:"Cancelado"}},
            codigo: {TIPO:"TP_STRING",MAXLENGTH: 25, VISIBILIDAD:"TP_VISIBLE"}
        }
    },
    mp: {
        ATRIBUTOS: {
            textomp: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"texto"}
        },
        FORM: [
            {
                textomp: '<div class="col-12">/textomp/</div>',
            }
        ]
    },
    pl: {
        ATRIBUTOS: {
            textopl: {TIPO:"TP_TEXT",EDITOR:1,VISIBILIDAD:"TP_VISIBLE",NOMBRE:"texto"}
        },
        FORM: [
            {
                textopl: '<div class="col-12">/textopl/</div>',
            }
        ]
    },
    tb: {
        ATRIBUTOS: {
            banco: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE"},
            tipo: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE"},
            nro: {TIPO:"TP_ENTERO",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"número"},
            suc: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"sucursal"},
            nombre: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE"},
            cbu: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"C.B.U."},
            cuit: {TIPO:"TP_STRING",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"C.U.I.T."},
            emailpago: {TIPO:"TP_EMAIL",VISIBILIDAD:"TP_VISIBLE",NOMBRE:"Email para mandar comprobante"},
        },
        FORM: [
            {
                banco: '<div class="d-flex col-12 col-md-6">/banco/</div>',
                nro: '<div class="col-12 col-md-3">/nro/</div>',
                suc: '<div class="col-12 col-md-3">/suc/</div>',
            },
            {
                tipo: '<div class="d-flex col-12 col-md-6">/tipo/</div>',
                cbu: '<div class="col-12 col-md-6">/cbu/</div>',
            },
            {
                nombre: '<div class="col-12 col-md-6">/nombre/</div>',
                cuit: '<div class="col-12 col-md-6">/cuit/</div>',
            },
            {
                emailpago: '<div class="col-12 col-md-6">/emailpago/</div>',
            },
        ]
    },
};