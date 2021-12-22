import { Accordion, AccordionTab } from 'primereact/accordion';
import React, { useRef } from 'react';

const demoIntros = [
  {
    title: 'What is a theory of change (TOC)',
    getDescription: () => (
      <>
        <p dir="ltr">
          A ToC is an “explicit, testable model of how and why change is expected to happen along an
          impact pathway in a particular context. A basic research-for-development (R4D) ToC
          identifies the context and key actors in a system and specifies the causal pathways and
          mechanisms by which the research aims to contribute to outcomes and impacts.” (MELCOP,
          2019). It is the explicit articulation of this change process, from activities and outputs
          through to desired outcomes and impacts, that is the hallmark of a ToC.
        </p>
        <p dir="ltr">
          A ToC is typically presented as a diagram and/or as a narrative description. OneCGIAR uses
          both to capture and communicate the key ideas as completely as possible. A ToC diagram
          takes the form of a flow chart with boxes to illustrate the key steps in the change
          process and arrows to show the causal linkages between activities, outputs, outcomes, and
          impacts. The narrative and the diagram complement one another. The ToC narrative provides
          a more complete description of the context, assumptions, and causal logic, and provides
          space for more detail and nuance. It explains what the Initiative/WP will do, who it will
          work with, and how that is expected to lead to the intended outcomes and, ultimately,
          impacts. Presenting the ToC in story form helps bring it to life and thinking about the
          TOC in a different way may help identify gaps or flaws in the logic. Some readers will
          more easily digest one form or the other.
        </p>
      </>
    ),
  },
  {
    title: 'Why develop and use a TOC',
    getDescription: () => (
      <>
        <p dir="ltr">
          The process of developing a ToC is intended to support and document an Initiative’s change
          strategy. It encourages research design teams, research managers, and researchers to
          analyze and plan how the work they do, and the outputs they produce, will inform, support,
          guide and otherwise influence other system actors and processes, and how the resulting
          actions and processes will contribute to the intended impact. The process:
        </p>
        <ul>
          <li dir="ltr">
            <p dir="ltr">Supports system-level strategic planning;</p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Encourages critical thinking, integration, and collective visioning among team members
              and partners;
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">Facilitates co-ownership of the research process;</p>
          </li>
          <li dir="ltr">
            <p dir="ltr">Supports transparency and accountability to results;</p>
          </li>
          <li dir="ltr">
            <p dir="ltr">Helps identify and engage key actors at program boundaries; and</p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Builds appreciation and understanding of the diverse roles required in change
              processes.
            </p>
          </li>
        </ul>
        <p dir="ltr">
          When used ex ante, a ToC provides a framework and guide for initiative planning and
          implementation. Used throughout a research initiative’s work, a ToC can support monitoring
          and learning and inform adaptive management. ToC also supports ex post evaluation,
          providing a set of hypotheses and the basis for indicators and measures of change that can
          be tested empirically.
        </p>
      </>
    ),
  },
  {
    title: 'Key concepts and definitions',
    getDescription: () => (
      <>
        <p dir="ltr">
          Research, or any other intervention in a complex system, cannot directly “cause” impact.
          Rather, a research program can produce knowledge, technology and other innovations. In
          engaged research-for-development, a research program also provides services and
          contributes to processes, such as training, networking, or technical support. These
          products and services of research can then inform, enable, facilitate, support, or
          otherwise influence other system actors and processes, and thereby contribute to the
          changes needed to realize the intended impact. This can be modelled in a ToC as a series
          of three spheres that represent the declining relative influence of the research and its
          outputs as they interact with other actors and processes over time. Key concepts in the
          TOC are explained below and illustrated in Fig. 1.
        </p>
        <p dir="ltr">
          The Sphere of Control encompasses the activities and outputs produced directly by the
          Initiative and over which the Initiative has control.
        </p>
        <ul>
          <li dir="ltr">
            <p dir="ltr">
              Activities are the actual work done by the Initiative. Activities are defined as a
              measurable amount of work performed to convert inputs (i.e. time and resources) into
              outputs or innovations. This includes everything from background scoping, literature
              review, through analysis, innovation design and scaling, as well as capacity
              development, communications and stakeholder engagement.
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Outputs are the knowledge, technical or institutional advances produced by CGIAR
              research, as well as engagement and capacity development activities. Examples of
              outputs include new research methods, policy analyses, gene maps, new crop varieties
              and breeds, institutional innovations, or other products of research work.
            </p>
          </li>
        </ul>
        <p dir="ltr">
          The Sphere of Influence encompasses the system actors and processes that are influenced by
          Initiative activities and outputs through direct engagement and/or uptake and use of
          outputs.
        </p>
        <ul>
          <li dir="ltr">
            <p dir="ltr">
              System Actors: Individuals or organizations operating as part of the system the
              Initiative aims to influence, whose actions can advance or impede the Initiative’s
              aims.
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Outcome is a change in knowledge, skills, attitudes and/or relationships (KASR), which
              manifests as a change in behavior in particular system actors, to which research
              outputs and related activities have contributed. Examples of outcomes include: use of
              a new technology (including outputs like a seed variety) by farmers; policy actors
              using research-based knowledge to inform policy decisions; participants in a
              CGIAR-supported process agree to a new germplasm conservation and exchange protocol;
              other researchers use CGIAR generated methods and/or data-bases.
            </p>
          </li>
        </ul>
        <p dir="ltr">
          The Sphere of Interest encompasses indirect changes that are outside the direct influence
          of the initiative. These may include both high-level outcomes (changes in KASR and
          behavior) and impacts.
        </p>
        <ul>
          <li dir="ltr">
            <p dir="ltr">
              Impact is a durable change in the condition of people and their environment brought
              about by a chain of events or change in how a system functions to which research,
              innovations and related activities have contributed. It refers to a change of state
              (e.g. nutritional status; farm productivity; household wealth; gender representation
              in land rights) or flow (e.g. average annual income; CO2 emissions).
            </p>
          </li>
        </ul>
        <p dir="ltr">
          With these elements in place, the ToC model represents the sequence of events in the
          change process(es) to which the intervention is contributing, answering “Who will do what
          differently as a result of the Initiative?”. A complete ToC must also explain the causal
          logic, answering “Why will these changes happen?”.
        </p>
        <ul>
          <li dir="ltr">
            <p dir="ltr">
              Assumptions are conditions that are likely necessary for the causal chain behind an
              intervention to hold. It is useful to distinguish between: i. theoretical assumptions,
              about how the intervention is expected to contribute to a process of change, and; ii.
              contextual assumptions about context, current conditions and the trajectory and risks
              that could affect the progress or success of a development intervention.
            </p>
          </li>
        </ul>
        <p dir="ltr">
          Most research initiatives will operate through more than one or several pathways; that is,
          through constellations of actors and processes leading to changes in, for example, a
          technology, the policy environment in which the technology will be used, and the capacity
          of system actors to use the technology. Each of these can be characterized as an impact
          pathway.
        </p>
        <p dir="ltr">
          An Impact Pathway is a sub-component of a ToC. It is the sequence of outputs, outcomes and
          the relevant assumptions and causal logic that explain a particular high-level outcome
          within a theory of change.
        </p>
      </>
    ),
  },
  {
    title: 'Nested TOCs',
    getDescription: () => (
      <>
        <p dir="ltr">
          Because of the uncertain nature of research, and the inherently indirect and complex
          pathways that lead to impacts, a ToC approach is particularly well suited to R4D. However,
          there are trade-offs in modeling complex systems; as the scale and coverage become broader
          and more comprehensive, ToCs necessarily become less detailed and precise.
        </p>
        <p dir="ltr">
          This limitation can be overcome by nesting ToCs, with an over-arching but general ToC,
          supported by increasingly more detailed and specific ToCs. Nested ToCs can capture
          elements of a large and complex implementation design in a way that allows ToC to be used
          for multiple purposes, including strategic planning and design, communications, reflection
          and adaptive management, monitoring and evaluation of progress and of key causal claims.
          CGIAR will have an overall CGIAR ToC and four levels of nested ToCs for Initiatives:
          Action Area (N); Initiative (N-1)); Work Package (N-2), and; Innovation Package/Country
          (N-3). Analogous ToC levels will be used for other (non-Initiative) CGIAR projects.
        </p>
        <p dir="ltr">
          There will also be interactions across Initiatives, with some Initiatives using outputs
          and/or contributing to joint outcomes with other Initiatives. Those interactions, as well
          as links to other CGIAR projects and country-level strategies, should be modelled in the
          appropriate ToCs.
        </p>
      </>
    ),
  },
  {
    title: 'Who is involved in TOC development and use',
    getDescription: () => (
      <>
        <p dir="ltr">
          While it is possible for an individual or a small team to develop a ToC, the process can
          be as important as the ToC itself. It is an opportunity to engage other system actors to
          learn about their interests, objectives and intentions, to share problem analyses, and to
          co-develop ideas and plans to achieve shared objectives, as well as to mitigate potential
          conflicting objectives.
        </p>
        <p dir="ltr">
          The extent of participation depends on the scale to the ToC. Ideally, ToCs at the more
          detailed level (e.g. N-3 Innovation Package or County level) will be co-developed with the
          main stakeholders and partners from government, private sector, NGO and civil society.
        </p>
        <p dir="ltr">
          For higher-level (N, N-1, N-2), multi-country ToCs, it will not be possible to engage all
          stakeholders. Instead ToCs at this level will be developed with representatives of key
          stakeholder groups. Ideally, those representatives will be actual stakeholders and
          partners from priority countries/contexts. The process can begin with a draft ToC shared
          for comment and input and usually involves a virtual or in-person facilitated workshop. It
          is helpful to have a knowledgeable facilitator and/or a resource person familiar with ToC
          concepts and approaches to help guide the process. It is useful to begin by thinking about
          the development challenge, and the needs and opportunities in the particular context, to
          identify what high level outcomes and impacts the Initiative aims to contribute to. Then
          there is an iterative process of identifying, analyzing, and strategizing about the
          processes and actors needed to realize those outcomes and impacts, and specifying what the
          Initiative needs to do/produce to reach those targets.
        </p>
        <p dir="ltr">
          In practice, full stakeholder engagement requires time to build relationships and support
          genuine collaboration. Moreover, it is neither practical or ethical to consume time and
          resources and raise expectations of partners and stakeholders before an Initiative is
          approved and commitments can be made. It may be necessary to build first iteration ToCs
          prior to full stakeholder engagement. In that case, it is important to draw on knowledge
          and experience within the team, secondary information, and other inputs, to understand,
          anticipate and model the system as well as possible. The ToC can then be revisited and
          refined when time permits a more thorough engagement process.
        </p>
      </>
    ),
  },
  {
    title: 'How to develop a TOC',
    getDescription: () => (
      <>
        <p dir="ltr">Process and key considerations (general, for ToC at any scale)</p>
        <p dir="ltr">
          Ideally, any strategy development process, including developing a ToC, should begin with a
          clear and thorough analysis of the problem and determination of goals, and then work
          backwards to define the best way to achieve those goals. In practice, it is rare to start
          with a “clean slate”. OneCGIAR has identified the Impact Areas and SDG targets that the
          portfolio will contribute to, and substantial work has already been done during the
          Initial Design Phase to identify development challenges and begin planning activities and
          draft first iteration Initiative ToCs. Moreover, many Initiatives will build on previous
          research and relationships. In any case, ToC development is a highly iterative process
          that accommodates flexible sequencing. With a first draft in place, it is recommended to:
        </p>
        <p dir="ltr">
          1. Review and reflect on the Impact Areas and define the impact targets as specifically as
          possible, utilizing available literature and analyses, and the expert knowledge of
          partners to help appreciate the context, needs, and opportunities in the relevant
          geographic areas;
        </p>
        <p dir="ltr">
          2. Specify highest-level outcomes, identifying which actors will need to take what actions
          for the impact targets to be realized (Note: These correspond to the Action Area (AA)
          outcomes in the AA ToCs). Outcomes at this level may not be realized within the timeframe
          of the Initiative, and the Initiative will not be accountable for them in the current
          phase. Nevertheless, the AA outcomes are part of the causal logic and, if they are well
          specified, it helps analyze and identify the necessary antecedent outcomes.
        </p>
        <p dir="ltr">
          3. After reviewing and refining impacts and AA outcomes, shift the focus to activities and
          outputs to produce a provisional list of the main products and services that the
          Initiative will deliver. Research questions and the methods used to answer those questions
          should be clearly specified, but it is important to think beyond scientific and technical
          outputs and consider what partners will be involved and how they will be engaged.
        </p>
        <p dir="ltr">
          4. The model is now populated at either end, with a provisional set of outputs and with AA
          outcomes and impacts identified. It is now necessary to identify the outcomes that are
          expected to result when partners and other system actors are informed, enabled, supported,
          encouraged, or otherwise influenced by the Initiative’s outputs to take actions that they
          would not otherwise have taken. These outcomes should be specified by actor or actor group
          (depending on the level of the ToC, with more specificity at lower levels) with actions
          defined in absolute terms if possible. For example, an outcome such as “20% of seed sold
          by private seed companies in countries x, y and z is of new varieties from CGIAR/NARS
          network” provides clearer guidance, and is more easily evaluated, than “More seed
          companies in target countries access and distribute new varieties from CGIAR/NARS
          network”.
        </p>
        <p dir="ltr">
          6. Analyze and document the theoretical and contextual assumptions underlying each of the
          main causal relationships to explain how and why each result is expected to be realized.
          Why should actor X be expected to use or be influenced by an output or set of outputs
          generated by the Initiative? Why should the resulting actions of Actor X and Actor Y lead
          to actions by Actor Z and why should that contribute to the high-level outcomes and/or
          impacts targeted by the Initiative?
        </p>
        <p dir="ltr">
          7. Review, revise and refine the ToC model to ensure that the main actors and activities
          are identified (including other WPs/Initiatives), that the causal logic underlying each
          step is sound, and that the whole program logic is sound and adequate to contribute
          effectively to the main outcomes and impacts.
        </p>
        <p dir="ltr">
          8. Identify End-of-Initiative (EoI) outcomes. These are the highest-level outcomes in the
          model that are ambitious but could reasonably be realized within the time and resources
          available to the Initiative.
        </p>
        <p dir="ltr">
          9. Review, reconfirm and revise as necessary to ensure that the TOC is coherent, complete,
          and logically sound. It should have plausible causal links from research questions and
          research and supporting activities, through outputs to EoI Outcomes and on to AA Outcomes.
        </p>
        <p dir="ltr">
          These are the basic steps. For the process to be effective, there is need for ongoing
          validation and vetting of the ToC model with partners and other stakeholders. This helps
          make the ToC more accurate, effective, and transparent, and the participation process
          generates ownership in the model to support accountability and increases the effectiveness
          of the Initiative.
        </p>
        <p dir="ltr">
          The ToC development process provides a forum and framework for bridging different
          disciplines, methods and knowledge sources. The ToC process surfaces participants’ ideas,
          perspectives and approaches, and stimulates them to contrast assumptions and expectations
          of the desired change. Participants build on each other’s ideas as they identify a
          collective purpose and set of outcomes. Moreover, participants can identify convergences
          between different activities involving multiple actor groups, as well as those which feed
          into one or several impact pathways. This helps make research more results-oriented and
          representative of the diverse perspectives, experience and expertise within the team and
          the workshop participants.
        </p>
        <p dir="ltr">Action Area (N) ToC process, participants and content</p>
        <p dir="ltr">
          The AA ToCs show how Initiatives interact and, in combination, contribute to the primary
          AA impact pathways and intended impacts. At the AA and Initiative scales, the generic
          categories of “demand partners”, “innovation partners” and “scaling partners” are useful
          for indicating key actor groups (e.g. government policy makers; private sector seed
          companies; environmental NGOs). In practice, many partners may play more than one of these
          roles.
        </p>
        <p dir="ltr">
          The Action Area ToCs are available at&nbsp;
          <a
            href="https://www.cgiar.org/research/investment-prospectus/"
            target="_blank"
            rel="noreferrer"
          >
            CGIAR Investment Prospectus
          </a>
        </p>
        <p dir="ltr">Initiative (N-1) ToC process, participants and content</p>
        <p dir="ltr">
          At the Initiative level, the ToC should identify the development challenge the Initiative
          is tackling and the priority constraints and opportunities that can be addressed through
          research (i.e. the research problems). It should present the research activities and
          supporting activities (e.g. capacity development; engagement in policy processes) that
          will be done within each of its constituent WPs and what outputs and outcomes will result.
          It should identify End-of-Initiative Outcomes and show how those EoI outcomes are expected
          to contribute to AA outcomes and to Impact.
        </p>
        <p dir="ltr">
          First draft ToCs can be developed based on prior work, consultation, new strategic
          analysis and expert judgement. It will be valuable to identify and consult with
          representatives of potential partners, and other key system actors operating in the
          relevant area (thematically and geographically), to refine and improve the problem
          definition, to learn their objectives and plans, to identify opportunities for
          collaboration and for mitigating conflicts, and to focus the overall Initiative strategy.
          As noted above, there are always practical constraints on full stakeholder engagement.
          This can be mitigated to some extent by: 1. Building on prior knowledge and secondary
          information to understand the problem context and anticipate stakeholder needs and
          expectations, and; 2. Consulting stakeholders as soon and as fully as possible and
          revising the ToC accordingly.
        </p>
        <p dir="ltr">
          The Initiative ToC should identify the most important outputs to be produced by the set of
          WPs, including both products and services, as clearly specified individual bullet points.
        </p>
        <p dir="ltr">
          Outcomes should be specified by actor and action. Ideally, each outcome should correspond
          to a single actor group. As a rule of thumb, if several organizations/organization types
          are likely to be reached/influenced by similar knowledge and processes, and if they are
          likely to respond in similar ways, this can be treated as a single outcome. Otherwise,
          they should be treated as separate outcomes. The causal logic (i.e. theoretical reasoning)
          for each linkage should be explained in sufficient detail. Collectively, the list of
          outcomes in the Initiative ToC should include all the most important End-of-Initiative
          outcomes.
        </p>
        <p dir="ltr">
          The ToC narrative should also explain the main subsequent outcomes (i.e. AA outcomes ) and
          the causal logic needed to reach impact, even though those outcomes are not expected to be
          realized within the time frame of the Initiative. This is necessary to be able to assess
          the overall ToC.
        </p>
        <p dir="ltr">Work Package (N-2) ToC process, participants and content</p>
        <p dir="ltr">
          At the WP scale, it is possible and necessary to include more detail about the kinds of
          partners and the nature of engagement, and to specify outcomes (who will do what
          differently as a result of the WP) by actor group or, if possible, by specific actor (i.e.
          by name).
        </p>
        <p dir="ltr">
          The ToC narrative should identify the challenge and the main gaps/constraints as
          specifically as possible, identify what the research needs are (i.e. research problems)
          and indicate what the priority research focus will be. Ideally, the WP should focus on a
          set of priority research problems that need to be solved and the supporting activities
          (e.g. capacity development; engagement in policy processes) needed to realize the intended
          change.
        </p>
        <p dir="ltr">
          Ideally, the process of developing and refining the ToC at WP level should involve key
          stakeholders and partners to refine and improve the problem definition, to learn their
          objectives and plans, to identify opportunities for collaboration and for mitigating
          conflicts, and to focus the overall Initiative strategy. In practice, this engagement work
          may have to be done after Initiative approval. It can be done asynchronously, by e-mail
          and bilateral meetings and synchronously in a workshop. After approval, ToCs can be
          reviewed and refined with a broader participation during Initiative/WP inception
          workshops. The workshop process can add value by providing a forum for networking,
          information sharing and co-development of objectives, strategies and plans.
        </p>
        <p dir="ltr">
          The WP ToC should identify: research questions the WP will focus on; key research
          activities and outputs; key supporting activities and outputs; the main actor groups the
          WP will engage with, including specific examples from priority countries/contexts;
          intended outcomes defined in terms of actions by other system actors; clear explanation of
          the causal logic for each outcome, with attention to variations in the relevant context in
          priority countries/regions; end-of-initiative outcomes.
        </p>
        <p dir="ltr">
          At the WP level, it is likely that there will be some outcomes that result from outputs
          and that themselves contribute to subsequent outcomes; if an outcome is important in the
          causal logic, it should be modelled in the ToC.
        </p>
        <p dir="ltr">
          For presenting the WPs in the Initiative ToC, each WP title should communicate as clearly
          as possible the essence of what the WP will do, along with a short statement to indicate
          the main research focus/activities and the main support activities to be done by the WP.
        </p>
        <p dir="ltr">Innovation Package/Country (N-3) ToC process, participants and content</p>
        <p dir="ltr">
          ToC development at the country level follows the same principles as at the WP level, with
          attention to the specific conditions, processes and actors in each context. They will
          integrate multiple WPs within impact pathways defined by key actors/processes. Those
          impact pathways will likely represent the AAs pathways at the country level. They will be
          important for planning and coordinating planning, partnerships and outreach.
        </p>
        <p dir="ltr">
          ToCs at this level are not required in the current cycle of proposal development and it
          will be defined before implementation for approved initiative as part of the inception
          phase.
        </p>
      </>
    ),
  },
  {
    title: 'How TOCs will be evaluated',
    getDescription: () => (
      <>
        <p dir="ltr">
          ToCs will be evaluated as part of Initiative proposal evaluation. ToCs at this stage need
          to be well articulated, clear, and sound. The narrative should demonstrate a good
          understanding of the problem context, with a description of the system, including the key
          actors and processes, within which the Initiative and its WP will operate. It should
          provide an explanation of both the scientific and social-political rationales and
          assumptions guiding the design.
        </p>
        <p dir="ltr">
          The nested set of ToCs should collectively map activities, outputs and outcomes that will
          contribute to the 5 Impact Areas and SDG targets, and situate the 3-year Initiative within
          a longer timeframe (e.g. 10 years). That is, the set of ToCs should model the entire
          change process, from start of Initiative (building on work that has been done before)
          through to impact, and should identify realistic end-of-initiative (EoI) outcomes.
        </p>
        <p dir="ltr">
          WP ToCs should be specified in sufficient detail that it will be possible to test them
          empirically; in other words, key outcomes should indicate what actor(s) or actor groups
          will take what action(s), and why.
        </p>
        <p dir="ltr">
          The nested ToCs (at Initiative, work pack and innovation package scale) provide enough
          detail for an evaluator to appreciate:
        </p>
        <ol>
          <li dir="ltr">
            <p dir="ltr">
              Main Impact pathways: What high-level outcomes and impacts does the initiative aim to
              contribute to and how?
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Key outputs: what are the main advances in knowledge and/or technology and what are
              the main process supports (e.g. capacity building; networking; empowerment) the
              Initiative aims to deliver?
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Key partners: what organizations and individuals will the Initiative engage with and
              what role will those actors play in the change process?
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Other system actors: what other organizations and individuals (beyond partners) will
              be involved in the change process and how?
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Key outcomes: Who will do what differently as a result of the Initiative.
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Assumptions: What are the contextual and theoretical assumptions underlying the causal
              logic of the Initiative?
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              Evaluable End-of-Initiative outcomes: What challenging but achievable outcomes will be
              realized by the end of the proposed phase of the Initiative?
            </p>
          </li>
          <li dir="ltr">
            <p dir="ltr">
              In future evaluations (i.e. after Initiative inception), evaluators will also consider
              ongoing efforts by the Initiative to review and update the ToC
            </p>
          </li>
        </ol>
        <p dir="ltr">
          Note that the Quality of Research for Development (QoR4D) assessment framework, that will
          be used in proposal evaluation considers principles of relevance and legitimacy, and
          requires “Evidence that the Initiative is demand driven through codesign with key
          stakeholders and partners…”.
        </p>
      </>
    ),
  },
  {
    title: 'Glossary of terms',
    getDescription: () => (
      <>
        <p dir="ltr">
          Activities: A measurable amount of work performed to convert inputs (i.e. time and
          resources) into outputs or innovations. This includes everything from background scoping,
          literature review, through analysis, innovation design and scaling, as well as capacity
          development, communications and stakeholder engagement.
        </p>
        <p dir="ltr">Actors (see System Actors)</p>
        <p dir="ltr">
          Assumptions: conditions that are likely necessary for the causal chain behind an
          intervention to hold. It is useful to distinguish between:
        </p>
        <p dir="ltr">
          i. theoretical assumptions, about how the intervention is expected to contribute to a
          process of change, and;
        </p>
        <p dir="ltr">
          ii. contextual assumptions about context, current conditions and the trajectory and risks
          that could affect the progress or success of a development intervention.
        </p>
        <p dir="ltr">
          Change: A generic term to mean any difference. Specific changes resulting from a research
          output are to be characterized as outcomes or impacts and they will require specific
          indicators.
        </p>
        <p dir="ltr">
          Contribution: Causal relationship in which an intervention (e.g. an Initiative) is one of
          two or more causal elements leading, independently or in combination, to a change.
        </p>
        <p dir="ltr">
          Impact: A durable change in the condition of people and their environment brought about by
          a chain of events or change in how a system functions to which research, innovations and
          related activities have contributed. It refers to a change of state (e.g. nutritional
          status; farm productivity; household wealth; gender representation in land rights) or flow
          (e.g. average annual income; CO2 emissions).
        </p>
        <p dir="ltr">
          Impact pathway: The sequence of outputs, outcomes and the relevant assumptions and causal
          logic that explain a particular high-level outcome within a theory of change. An Impact
          Pathway is a sub-component of a ToC.
        </p>
        <p dir="ltr">
          Inputs: The financial, human, and material resources used in research and development work
          conducted by the CGIAR.
        </p>
        <p dir="ltr">
          Outcome: A change in knowledge, skills, attitudes and/or relationships, which manifests as
          a change in behavior, to which research outputs and related activities have contributed.
        </p>
        <p dir="ltr">
          End-of-Initiative (Program) Outcome: An outcome that is challenging but reasonable to
          expect within the timeframe and resources of the Initiative (program) and observable at
          the conclusion of an Initiative (or program), and is therefore testable during
          post-project evaluation.
        </p>
        <p dir="ltr">
          Results : A collective term referring to one or more outputs, outcomes or impacts of an
          intervention.
        </p>
        <p dir="ltr">
          Sphere of control: An element of a conceptual model of the CGIAR Results Based Management
          (RBM) system that refers to actions under direct control of the Initiative (program) that
          result in outputs. Covers CGIAR research, innovations, services and output delivery.
        </p>
        <p dir="ltr">
          Sphere of influence: An element of a conceptual model of the CGIAR RBM system that refers
          to actions of system actors that can be influenced directly by the Initiative (program)
          (i.e. by outputs), defined as outcomes.
        </p>
        <p dir="ltr">
          Sphere of interest: An element of a conceptual model of the CGIAR RBM system that includes
          outcomes and impacts that can only be influenced indirectly by the program (i.e. by the
          actions of other system actors = outcomes).
        </p>
        <p dir="ltr">
          System Actors: Individuals or organizations operating as part of the system the Initiative
          aims to influence and whose actions can advance or impede the Initiative’s aims.
        </p>
      </>
    ),
  },
];

const Intro = () => {
  const intros = useRef(demoIntros || []);

  return (
    <div className="layout-dashboard p-pb-5">
      <div className="p-d-grid">
        <div className="p-col-12 p-md-10">
          <Accordion multiple activeIndex={[0]}>
            {intros.current.length &&
              intros.current.map(({ title, getDescription }) => (
                <AccordionTab key={title} header={title}>
                  <div>{getDescription()}</div>
                </AccordionTab>
              ))}
          </Accordion>
        </div>
      </div>
    </div>
  );
};

export default Intro;
