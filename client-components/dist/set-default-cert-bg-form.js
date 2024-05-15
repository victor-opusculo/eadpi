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
        current_id: null,
        enableMediaSelect: false
    };

    const methods = 
    {
        changeInput(e)
        {
            this.render({ ...this.state, current_id: e.target.value });
        },

        toggleMediaSelect()
        {
            this.render({ ...this.state, enableMediaSelect: !this.state.enableMediaSelect });
        },

        setMediaId(id)
        {
            this.changeInput({ target: { value: id } });
        },

        submit(e)
        {
            e.preventDefault();

            const headers = new Headers({ 'Content-Type': 'application/json' });
            const body = JSON.stringify({ data: { newId: this.state.current_id } });
            fetch(EADPI.Helpers.URLGenerator.generateApiUrl("/administrator/certificates/set_bg_image"), { headers, body, method: 'PUT' })
            .then(res => res.json())
            .then(json =>
            {
                EADPI.Alerts.pushFromJsonResult(json);
            })
            .catch(EADPI.Alerts.pushError("Erro ao salvar configuração!"));
        }
    };


  const __template = function({ state }) {
    return [  
    h("form", {"onsubmit": this.submit.bind(this)}, [
      h("ext-label", {"label": `Imagem de fundo (Mídia ID)`}, [
        h("input", {"type": `number`, "min": `1`, "step": `1`, "value": state.current_id, "onchange": this.changeInput.bind(this)}, ""),
        h("button", {"type": `button`, "class": `btn ml-2`, "onclick": this.toggleMediaSelect.bind(this)}, `Procurar`)
      ]),
      ((state.enableMediaSelect) ? h("media-client-select", {"set_id_field_callback": this.setMediaId.bind(this)}, "") : ''),
      h("div", {"class": `mt-2 text-center`}, [
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

  
