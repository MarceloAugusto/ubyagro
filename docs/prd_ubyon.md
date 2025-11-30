**PROJETO:** **UbyOn** **(MVP)** **Documento** **de** **Especificação**
**Técnica** **e** **Funcional**

**1.** **Visão** **Geral** **e** **Contexto**

O objetivo é desenvolver uma plataforma Desktop (Web) de Inteligência
Competitiva Técnica para a UbyAgro. A solução visa resolver a
ineficiência do processo de "Exploração" de novos produtos, onde times
perdem tempo garimpando dados manualmente em fontes dispersas.

O sistema deve centralizar dados Regulatórios, de Patentes, Técnicos e
de Mercado para validar a viabilidade de novas ideias antes de
investimentos pesados.

**2.** **Arquitetura** **Técnica** **e** **Segurança**

> ● **Tipo** **de** **Aplicação:** Web App otimizado para Desktop.
>
> ● **Core** **de** **IA:** Integração via **API** **Enterprise** (ex:
> Azure OpenAI Service ou AWS Bedrock) para garantir privacidade
> contratual dos dados. **Restrição** **Crítica:** Os dados não podem
> ser usados para treinamento público do modelo.
>
> ● **Arquitetura** **de** **Dados** **(RAG** **-** **Retrieval**
> **Augmented** **Generation):**
>
> ○ O sistema deve utilizar uma Vector Database para indexar documentos
> PDF/CSV.
>
> ○ **Isolamento** **de** **Dados:**
>
> ■ **Global** **Context:** Documentos enviados pelo **Admin** tornam-se
> conhecimento acessível a *todos* os usuários.
>
> ■ **Session** **Context:** Documentos enviados por **Usuários**
> **Padrão** são indexados apenas para aquela sessão/usuário específico
> e não "contaminam" a base global.

**3.** **Perfis** **de** **Usuário** **e** **Permissões** **(RBAC)**

O sistema deve implementar controle de acesso baseado em funções (RBAC):

> 1\. **Administrador** **(Admin):**
>
> ○ Vê tudo e configura o sistema.
>
> ○ Acesso total à tela de "Configurações" (Painel Administrativo).
>
> ○ Pode inserir Chaves de API Globais (ex: Agrofit, Scielo) e Upload de
> Documentos Globais.
>
> 2\. **Usuários** **por** **Departamento** **(P&D,** **DTM,** **MKT,**
> **Comercial):**
>
> ○ Acesso setorizado. Exemplo: P&D pode ver detalhes de moléculas que o
> Comercial não vê.
>
> ○ Podem fazer upload de arquivos para análise própria (privada), mas
> não alteram a base de conhecimento global da empresa.

**4.** **Funcionalidades** **do** **MVP** **(Escopo)** **4.1.**
**Painel** **de** **Configurações** **(Admin** **&** **User)**

> ● **Para** **Admin:**
>
> ○ Campos para inserção de API Keys de serviços externos (pagos ou
> governamentais).
>
> ○ Área de Upload de "Base de Conhecimento Oficial" (treinamento
> global). ● **Para** **Usuário** **Comum:**
>
> ○ Área de Upload de "Arquivos de Apoio" (contexto local para a
> pesquisa atual).

**4.2.** **Interface** **de** **Pesquisa** **(Search** **Engine)**

> ● **Input:** Barra de pesquisa simples (estilo Google/Perplexity).
>
> ● **Filtros** **de** **Escopo** **(Obrigatório):** Dois botões
> visíveis próximos à barra de pesquisa: 1. \[Pesquisa Nacional\]:
> Prioriza fontes em PT-BR (MAPA, Embrapa, IBGE).
>
> 2\. \[Pesquisa Internacional\]: Prioriza fontes globais e traduz
> termos automaticamente (WIPO, Google Scholar Global).
>
> ● **Processamento:** O sistema deve cruzar a query do usuário com: 1.
> Base vetorial interna (Uploads).
>
> 2\. APIs conectadas (Mockadas ou Reais conforme config).

**4.3.** **Motor** **de** **Análise** **e** **Relatórios** **(Output)**

A IA deve processar os dados recuperados e gerar um **Texto**
**Estruturado**, analisando a viabilidade da ideia baseada nos seguintes
pilares:

> 1\. **Regulatório:** O que é permitido? (Normas MAPA, ANVISA).
>
> 2\. **Patentes:** O que é protegido? (Existe anterioridade? Quem são
> os donos?).
>
> 3\. **Técnico/Científico:** Existe embasamento? (Artigos, pesquisas,
> ingredientes ativos). 4. **Mercado:** Existe demanda? (Concorrentes,
> preços, lacunas no portfólio).

Critérios de Avaliação (Output Esperado):

O texto gerado deve cobrir explicitamente:

> ● Sustentabilidade e ESG.
>
> ● Análise de Concorrência e Barreiras de Entrada. ● Impacto Agronômico
> e Aplicabilidade.
>
> ● Riscos (Técnicos e de Mercado) e Mitigação.
>
> ● Viabilidade Econômica (Custo-benefício, Investimento).

**5.** **Requisitos** **Não-Funcionais** **e** **UX**

> ● **Design:** Minimalista, elegante e focado na facilidade de uso
> (poucos cliques).
>
> ● **Performance:** Para o MVP, a performance extrema não é prioridade,
> mas a precisão da resposta é.
>
> ● **Privacidade:** Dados sensíveis de P&D não devem ser exibidos para
> usuários de MKT/Comercial sem autorização.

**6.** **Roadmap** **Futuro** **(Notes** **for** **Code** **Structure)**

> ● Embora o MVP retorne texto estruturado (Markdown/HTML), estruture o
> backend para facilitar a futura implementação de exportação para
> **PDF** **timbrado** (padrão corporativo com logos e gráficos).

**Instruções** **para** **a** **IA** **(System** **Prompt** **Sugerido**
**para** **o** **Backend)**

*Ao* *configurar* *o* *comportamento* *da* *LLM* *no* *código,*
*utilize* *a* *seguinte* *persona:*

> "Você é um Especialista Sênior em Scouting Tecnológico e Inteligência
> Competitiva para o Agronegócio. Sua função é analisar dados dispersos
> e fornecer relatórios de viabilidade técnica e comercial para a
> UbyAgro. Você deve ser cético, analítico e focado em dados.
>
> **Regras** **de** **Análise:**
>
> 1\. Se a pesquisa for 'Nacional', foque em regulação brasileira
> (MAPA). Se 'Internacional', considere tendências globais.
>
> 2\. Nunca invente dados (alucinação). Se a informação não estiver nos
> documentos anexados ou nas APIs, declare 'Dados insuficientes'.
>
> 3\. Respeite a confidencialidade: não exponha dados marcados como
> 'Confidencial P&D' em resumos gerais se o contexto não permitir."
