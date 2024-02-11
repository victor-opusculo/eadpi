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
        password: "",
        email: "" 
    };

    const methods = 
    {
        submit(e)
        {
            e.preventDefault();
            const headers = new Headers({ 'Content-Type': 'application/json' });
            const body = JSON.stringify({ data: { email: this.state.email, password: this.state.password } });
            fetch(EADPI.Helpers.URLGenerator.generateApiUrl('/student/login'), { method: 'POST', headers, body })
            .then(res => res.json())
            .then(json => { EADPI.Alerts.pushFromJsonResult(json); if (json.success) window.location.href = EADPI.Helpers.URLGenerator.generatePageUrl('/students/panel'); })
            .catch(reason => EADPI.Alerts.push(EADPI.Alerts.types.error, String(reason)));

            return true;
        },

        changeEmail(e)
        {
            this.render({ ...this.state, email: e.target.value });
        },

        changePassword(e)
        {
            this.render({ ...this.state, password: e.target.value }); 
        }
    };


  const __template = function({ state }) {
    return [  
    h("form", {"class": `mx-auto max-w-[500px]`}, [
      h("ext-label", {"label": `E-mail`}, [
        h("input", {"type": `email`, "class": `w-full`, "value": state.email, "oninput": this.changeEmail.bind(this)}, "")
      ]),
      h("ext-label", {"label": `Senha`}, [
        h("input", {"type": `password`, "class": `w-full`, "value": state.password, "oninput": this.changePassword.bind(this)}, "")
      ]),
      h("div", {"class": `text-center`}, [
        h("button", {"class": `btn`, "type": `submit`, "onclick": this.submit.bind(this)}, `Entrar`)
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

  
