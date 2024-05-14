 // Lego version 1.0.0
  import { h, Component } from './lego.min.js'
   
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
      const childs = Array.from(this.childNodes);
      this.__originalChildren = childs.length && !this.__originalChildren?.length ? childs : this.__originalChildren;

       this.__state.slotId = `slot_${performance.now().toString().replace('.','')}_${Math.floor(Math.random() * 1000)}`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(`#${this.__state.slotId}`);
      if (slot)
         for (const c of this.__originalChildren)
             slot.appendChild(c);
            
      return rendered;
    };

  
    const state = 
    {
        id: null,
        name: '',
        description: '',
        begin: '',
        end: '',
        certificate_bg_media_id: null,

        enableCertBgMediaSearch: false
    };

    const methods = 
    {
        changeField(e)
        {
            this.render({ ...this.state, [ e.target.name ]: e.target.value });
        },

        toggleSearch()
        {
            this.render({ ...this.state, enableCertBgMediaSearch: !this.state.enableCertBgMediaSearch });
        },

        setMediaId(id)
        {
            this.render({ ...this.state, certificate_bg_media_id: id });
        },

        submit(e)
        {
            e.preventDefault();

            const data = {};
            for (const prop in this.state)
                if (prop !== 'enableCertBgMediaSearch')
                    data['partners:' + prop] = this.state[prop];

            const route = this.state.id
                ? EADPI.Helpers.URLGenerator.generateApiUrl(`/administrator/partners/${this.state.id}`)
                : EADPI.Helpers.URLGenerator.generateApiUrl(`/administrator/partners`);

            const headers = new Headers({ 'Content-Type': 'application/json' });
            const body = JSON.stringify({ data });
            fetch(route, { method: this.state.id ? 'PUT' : 'POST', headers, body })
            .then(res => res.json())
            .then(json =>
            {
                EADPI.Alerts.pushFromJsonResult(json);
                if (json.success && json.data?.newId)
                    window.location.href = EADPI.Helpers.URLGenerator.generatePageUrl(`/admin/panel/partners/${json.data.newId}`);
            })
            .catch(EADPI.Alerts.pushError("Erro ao salvar parceiro!"));
        }
    };


  const __template = function({ state }) {
    return [  
    h("form", {"onsubmit": this.submit.bind(this)}, [
      h("ext-label", {"label": `Nome da parceria`}, [
        h("input", {"type": `text`, "maxlength": `280`, "class": `w-full`, "name": `name`, "value": state.name, "required": ``, "onchange": this.changeField.bind(this)}, "")
      ]),
      h("ext-label", {"label": `Descrição`, "linebreak": `1`}, [
        h("textarea", {"name": `description`, "onchange": this.changeField.bind(this), "value": state.description, "class": `w-full`, "rows": `6`}, "")
      ]),
      h("ext-label", {"label": `Início`}, [
        h("input", {"type": `date`, "value": state.begin, "onchange": this.changeField.bind(this), "name": `begin`, "required": ``}, "")
      ]),
      h("ext-label", {"label": `Fim`}, [
        h("input", {"type": `date`, "value": state.end, "onchange": this.changeField.bind(this), "name": `end`}, ""),
` (Deixe em branco para parceria em vigor)
        `
      ]),
      h("ext-label", {"label": `Fundo do certificado para inscritos pelo link de parceria`}, [
        h("input", {"type": `number`, "step": `1`, "min": `1`, "name": `certificate_bg_media_id`, "value": state.certificate_bg_media_id, "required": ``, "onchange": this.changeField.bind(this)}, ""),
        h("button", {"type": `button`, "class": `ml-2 btn`, "onclick": this.toggleSearch.bind(this)}, `Procurar`)
      ]),
      ((state.enableCertBgMediaSearch) ? h("media-client-select", {"set_id_field_callback": this.setMediaId.bind(this)}, "") : ''),
      h("div", {"class": `text-center mt-2`}, [
        h("button", {"type": `submit`, "class": `btn`}, `Salvar`)
      ])
    ])
  ]
  }

  const __style = function({ state }) {
    return h('style', {}, `
      
      
    `)
  }

  // -- Lego Core
  export default class Lego extends Component {
    init() {
      this.useShadowDOM = false
      if(typeof state === 'object') this.__state = Object.assign({}, state, this.__state)
      if(typeof methods === 'object') Object.keys(methods).forEach(methodName => this[methodName] = methods[methodName])
      if(typeof connected === 'function') this.connected = connected
      if(typeof setup === 'function') setup.bind(this)()
    }
    get vdom() { return __template }
    get vstyle() { return __style }
  }
  // -- End Lego Core

  
