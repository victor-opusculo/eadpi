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
        id: 0,
        waiting: false,
        name: '',
        description: '',
        filename: null,
        files: null
    };

    const methods = 
    {
        changeField(e)
        {
            if (e.target.getAttribute('data-fieldname') === 'filename')
            {
                this.render({ ...this.state, files: e.target.files });
            }

            this.render({ ...this.state, [e.target.getAttribute('data-fieldname')]: e.target.value });
        },

        submit(e)
        {
            e.preventDefault();

            const headers = new Headers();
            const formData = new FormData();

            formData.append('media:name', this.state.name);
            formData.append('media:description', this.state.description);
            formData.append('mediaFile', this.state.files && this.state.files[0] ? this.state.files[0] : null);

            this.render({ ...this.state, waiting: true });

            const route = this.state.id ? 
                EADPI.Helpers.URLGenerator.generateApiUrl(`/administrator/media/${this.state.id}`)
                :
                EADPI.Helpers.URLGenerator.generateApiUrl('/administrator/media');

            fetch(route, { headers, body: formData, method: 'POST' })
            .then(res => res.json())
            .then(json => 
            {
                EADPI.Alerts.pushFromJsonResult(json)
               
                if (json.success && json.data?.newId)
                    window.location.href = EADPI.Helpers.URLGenerator.generatePageUrl('/admin/panel/media/' + json.data.newId);
                else
                    this.render({ ...this.state, waiting: false });
            })
            .catch(reason => EADPI.Alerts.push(EADPI.Alerts.types.error, String(reason)));
        }
    };


  const __template = function({ state }) {
    return [  
    h("form", {"onsubmit": this.submit.bind(this)}, [
      h("ext-label", {"label": `Nome`}, [
        h("input", {"type": `text`, "class": `w-full`, "maxlength": `140`, "required": ``, "data-fieldname": `name`, "value": `${state.name}`, "oninput": this.changeField.bind(this)}, "")
      ]),
      h("ext-label", {"label": `Descrição`, "linebreak": `1`}, [
        h("textarea", {"class": `w-full`, "row": `6`, "data-fieldname": `description`, "oninput": this.changeField.bind(this)}, `${state.description}`)
      ]),
      h("ext-label", {"label": `Arquivo`}, [
        h("input", {"type": `file`, "class": `file:btn`, "data-fieldname": `filename`, "required": state.id ? false : true, "onchange": this.changeField.bind(this)}, "")
      ]),
      h("div", {"class": `text-center mt-2`}, [
        h("button", {"type": `submit`, "class": `btn`, "disabled": state.waiting}, [
          ((state.waiting) ? h("loading-spinner", {"additionalclasses": `invert w-[1em] h-[1em]`}, "") : ''),
`
                Salvar
            `
        ])
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

  
